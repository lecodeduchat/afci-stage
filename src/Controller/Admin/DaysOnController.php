<?php

namespace App\Controller\Admin;

use App\Entity\DaysOn;
use App\Form\DaysOnType;
use App\Repository\DaysOnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Admin/jours-ouvres', name: 'daysOn')]
class DaysOnController extends AbstractController
{
    #[Route('/', name: '_index', methods: ['GET'])]
    public function index(DaysOnRepository $daysOnRepository): Response
    {
        return $this->render('admin/days_on/index.html.twig', [
            'days_ons' => $daysOnRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DaysOnRepository $daysOnRepository): Response
    {
        $daysOn = new DaysOn();
        $daysOnForm = $this->createForm(DaysOnType::class, $daysOn);
        $daysOnForm->handleRequest($request);

        if ($daysOnForm->isSubmitted() && $daysOnForm->isValid()) {
            $daysOnRepository->save($daysOn, true);

            return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_on/new.html.twig', [
            'days_on' => $daysOn,
            'daysOnForm' => $daysOnForm,
        ]);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(DaysOn $daysOn): Response
    {
        return $this->render('days_on/show.html.twig', [
            'days_on' => $daysOn,
        ]);
    }

    #[Route('/{id}/modifier', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DaysOn $daysOn, DaysOnRepository $daysOnRepository): Response
    {
        $daysOnForm = $this->createForm(DaysOnType::class, $daysOn);
        $daysOnForm->handleRequest($request);

        if ($daysOnForm->isSubmitted() && $daysOnForm->isValid()) {
            $daysOnRepository->save($daysOn, true);

            return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_on/edit.html.twig', [
            'days_on' => $daysOn,
            'daysOnForm' => $daysOnForm,
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, DaysOn $daysOn, DaysOnRepository $daysOnRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $daysOn->getId(), $request->request->get('_token'))) {
            $daysOnRepository->remove($daysOn, true);
        }

        return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
    }
}
