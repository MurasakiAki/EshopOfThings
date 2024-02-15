<?php
namespace App\Controller;

use Doctrine\ORM\Query\Expr;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function search_category($category): Response
    {
        $products = $this->productRepository->findBy([
            'category'=>$category,
        ]);

        return $this->render('search_page/search_page.html.twig', [
            'products' => $products,
        ]);
    }

    public function search_name(Request $request): Response
    {
        $name = $request->request->get('name_to_search');
    
        // Convert search term to lowercase for case-insensitive comparison
        $searchTerm = strtolower($name);
    
        // Use Doctrine Expression Language to create a query with the LIKE operator
        $queryBuilder = $this->productRepository->createQueryBuilder('p');
        $queryBuilder->where($queryBuilder->expr()->like('LOWER(p.name)', ':searchTerm'))
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    
        // Execute the query to find products containing the specified term in their name
        $products = $queryBuilder->getQuery()->getResult();
    
        return $this->render('search_page/search_page.html.twig', [
            'products' => $products,
        ]);
    }
    
}