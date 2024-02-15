<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    private $productRepository;
    private $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    public function addToCart($productId, Request $request, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $quantity = $request->request->getInt('quantity', 1);

        for ($i = 0; $i < $quantity; $i++) {
            $cart[] = $productId;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('homepage'); // Redirect to the homepage or any other appropriate page
    }

    public function viewCart(SessionInterface $session): Response
    {
        $cartItems = $session->get('cart', []);
        $finalPrice = 0;
        $finalPriceTaxless = 0;
        $products = [];

        foreach ($cartItems as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $products[] = $product;
                $finalPrice += $product->getPrice();
                $finalPriceTaxless += $product->getNoDPHPrice();
            }
        }

        return $this->render('cart/view_cart.html.twig', [
            'products' => $products,
            'finalPrice' => $finalPrice,
            'finalPriceTaxless' => $finalPriceTaxless,
        ]);
    }

    public function removeFromCart($productId, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $index = array_search($productId, $cart);

        if ($index !== false) {
            unset($cart[$index]);
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('view_cart'); // Redirect to the cart view page
    }

    public function makeOrder(): Response
    {
        return $this->render('cart/make_order.html.twig');
    }

    public function confirmOrder(Request $request, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            // Get order data from the form submission
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            $address = $request->request->get('address');
            $email = $request->request->get('email');
    
            // Create a new Order entity and set its properties
            $order = new Order();
            $order->setName($name);
            $order->setSurname($surname);
            $order->setAddress($address);
            $order->setEmail($email);
    
            // Fetch product IDs from the session
            $productIds = $session->get('cart', []);
    
            // Fetch products associated with the submitted product IDs and add them to the order
            foreach ($productIds as $productId) {
                $product = $this->productRepository->find($productId);
                if ($product) {
                    $order->addProduct($product);
                }
            }
    
            // Persist the order to the database
            $this->entityManager->persist($order);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('confirm_order_page');
        }
    
        return $this->render('make_order.html.twig');
    }
}