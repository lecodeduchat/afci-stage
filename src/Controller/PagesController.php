<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/la-chiropraxie', name: 'page_chiropraxie')]
    public function chiropraxie(): Response
    {
        return $this->render('pages/la-chiropraxie.html.twig');
    }

    #[Route('/votre-chiropracteur', name: 'page_chiropracteur')]
    public function chiropracteur(): Response
    {
        return $this->render('pages/votre-chiropracteur.html.twig');
    }

    #[Route('/les-soins', name: 'page_soins')]
    public function soins(): Response
    {
        return $this->render('pages/les-soins.html.twig');
    }
}
