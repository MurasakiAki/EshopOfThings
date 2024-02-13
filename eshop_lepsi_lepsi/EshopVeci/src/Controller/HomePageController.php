<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{

    public function __construct(
        private readonly ProductRepository $products
    )
    {
    }

    #[Route('/', name: 'homepage', methods:['GET'])]
    public function index(): Response
    {
        $product = (new Product())
        ->setName("Věc1")
        ->setPrice(10);

        $this->products->save($product);

        return $this->render('homepage.html.twig', [
            'name'=>$product->getName(),
            'price'=>$product->getPrice(),
            'karel'=>'Hi',
        ]);
    }

}
