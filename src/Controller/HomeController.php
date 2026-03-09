<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_index');
    }

    #[Route('/', name: 'app_index')]
    public function landing(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();

        $data = [
            'categories' => $categories,
            'products' => $products,
        ];

        if ($this->isGranted('ROLE_ADMIN')) {
            $productForm = $this->createForm(ProductType::class, new Product(), [
                'action' => $this->generateUrl('product_new'),
            ]);
            $categoryForm = $this->createForm(CategoryType::class, new Category(), [
                'action' => $this->generateUrl('category_new'),
            ]);
            $data['productForm'] = $productForm->createView();
            $data['categoryForm'] = $categoryForm->createView();
        }

        return $this->render('index.html.twig', $data);
    }

}