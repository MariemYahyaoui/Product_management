<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/category')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em, ProductRepository $productRepo, CategoryRepository $categoryRepo): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category created.');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('index.html.twig', [
            'products' => $productRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
            'modalForm' => $form->createView(),
            'modalTitle' => 'Create New Category',
            'modalSubtitle' => 'Add a new product category',
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em, ProductRepository $productRepo, CategoryRepository $categoryRepo): Response
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true, 'message' => 'Category updated.']);
            }

            $this->addFlash('success', 'Category updated.');
            return $this->redirectToRoute('app_index');
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('_category_edit_form.html.twig', [
                'form' => $form->createView(),
                'title' => 'Edit Category',
                'subtitle' => 'Update category information',
            ]);
        }

        return $this->render('index.html.twig', [
            'products' => $productRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
            'modalForm' => $form->createView(),
            'modalTitle' => 'Edit Category',
            'modalSubtitle' => 'Update category information',
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $token)) {
            $productCount = $category->getProducts()->count();
            $em->remove($category);
            $em->flush();
            if ($productCount > 0) {
                $this->addFlash('success', 'Category and its ' . $productCount . ' product(s) deleted.');
            } else {
                $this->addFlash('success', 'Category deleted.');
            }
        } else {
            // CSRF token invalid — useful for debugging in dev
            $this->addFlash('danger', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_index');
    }
}
