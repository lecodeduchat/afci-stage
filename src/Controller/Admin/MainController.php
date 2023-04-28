<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Je vérifie que l'utilisateur est bien connecté
        // et qu'il a le rôle: ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        return $this->render('admin/index.html.twig', compact('user'));
    }
}
