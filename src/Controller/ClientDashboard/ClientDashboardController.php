<?php

namespace App\Controller\ClientDashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;

#[Route('/client-dashboard')]
final class ClientDashboardController extends AbstractController
{
    #[Route('', name: 'app_client_dashboard')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('client_dashboard/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
