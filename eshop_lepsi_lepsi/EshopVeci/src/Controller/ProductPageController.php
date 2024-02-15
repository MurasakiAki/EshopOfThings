<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductPageController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function show($productId): Response
    {
        $product = $this->productRepository->find($productId);

        return $this->render('product_page/product_page.html.twig', [
            'product' => $product,
        ]);
    }
}