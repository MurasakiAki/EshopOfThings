<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('home_page/index.html.twig', [
            'products' => $products,
        ]);
    }
}