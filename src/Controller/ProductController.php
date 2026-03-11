<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $repository): Response
    {
        $products = $repository->findAll();

        // Optional: you can render a dedicated product page or homepage
        return $this->render('index.html.twig', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em, ProductRepository $productRepo, CategoryRepository $categoryRepo, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $this->uploadImage($imageFile, $product, $slugger);
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product created.');
            
            return $this->redirectToRoute('app_index');
        }

        return $this->render('index.html.twig', [
            'products' => $productRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
            'modalForm' => $form->createView(),
            'modalTitle' => 'Create New Product',
            'modalSubtitle' => 'Add a new product to your catalog',
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em, ProductRepository $productRepo, CategoryRepository $categoryRepo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product, [
            'action' => $this->generateUrl('product_edit', ['id' => $product->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Optional: Delete old image from server before uploading new one
                if ($product->getImagePath()) {
                    $oldPath = $this->getParameter('products_directory').'/'.$product->getImagePath();
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $this->uploadImage($imageFile, $product, $slugger);
            }

            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true, 'message' => 'Product updated.']);
            }

            $this->addFlash('success', 'Product updated.');
            return $this->redirectToRoute('app_index');
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('_edit_form.html.twig', [
                'form' => $form->createView(),
                'title' => 'Edit Product',
                'subtitle' => 'Update product information',
            ]);
        }

        return $this->render('index.html.twig', [
            'products' => $productRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
            'modalForm' => $form->createView(),
            'modalTitle' => 'Edit Product',
            'modalSubtitle' => 'Update product information',
        ]);
    }

    // Helper method to keep code clean
    private function uploadImage($imageFile, Product $product, SluggerInterface $slugger): void
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        try {
            $imageFile->move(
                $this->getParameter('products_directory'),
                $newFilename
            );
            $product->setImagePath($newFilename);
        } catch (FileException $e) {
            $this->addFlash('danger', 'Failed to upload image.');
        }
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepo): JsonResponse
    {
        $query = trim($request->query->get('q', ''));
        if ($query === '') {
            return $this->json([]);
        }

        $products = $productRepo->searchByNameOrCategory($query);

        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'category' => $product->getCategory() ? $product->getCategory()->getCategoryName() : null,
                'image' => $product->getImagePath(), // Added to search results
            ];
        }

        return $this->json($results);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $token)) {
            // Delete image file from server when deleting product
            if ($product->getImagePath()) {
                $filePath = $this->getParameter('products_directory').'/'.$product->getImagePath();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Product deleted.');
        }

        return $this->redirectToRoute('app_index');
    }

    #[Route('/product/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('baseee.html.twig', [
            'product' => $product,
        ]);
    }
}