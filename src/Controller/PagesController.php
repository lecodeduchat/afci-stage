<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/la-chiropraxie', name: 'page_chiropractic')]
    public function chiropraxie(): Response
    {
        return $this->render('pages/la-chiropraxie.html.twig');
    }

    #[Route('/votre-chiropracteur', name: 'page_chiropractor')]
    public function chiropracteur(): Response
    {
        return $this->render('pages/votre-chiropracteur.html.twig');
    }

    #[Route('/les-soins', name: 'page_cares')]
    public function soins(): Response
    {
        return $this->render('pages/les-soins.html.twig');
    }

    #[Route('/prix-et-tarifs', name: 'page_prices')]
    public function tarifs(): Response
    {
        return $this->render('pages/prix-et-tarifs.html.twig');
    }

    #[Route('/FAQ', name: 'page_faq')]
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig');
    }

    #[Route('/rgpd', name: 'page_rgpd')]
    public function rgpd(): Response
    {
        return $this->render('pages/rgpd.html.twig');
    }

    #[Route('/mentions-legales', name: 'page_legal_notices')]
    public function mentionsLegales(): Response
    {
        return $this->render('pages/mentions-legales.html.twig');
    }
}
