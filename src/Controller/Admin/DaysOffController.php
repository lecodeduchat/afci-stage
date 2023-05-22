<?php

namespace App\Controller\Admin;

use App\Entity\DaysOff;
use App\Form\DaysOffType;
use App\Repository\DaysOffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/indisponibilites', name: 'daysOff')]
class DaysOffController extends AbstractController
{
    #[Route('/', name: '_index', methods: ['GET'])]
    public function index(DaysOffRepository $daysOffRepository): Response
    {
        return $this->render('admin/days_off/index.html.twig', [
            'days_offs' => $daysOffRepository->findAll(),
        ]);
    }

    #[Route('/nouvelle', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DaysOffRepository $daysOffRepository): Response
    {
        $daysOff = new DaysOff();
        $form = $this->createForm(DaysOffType::class, $daysOff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $daysOffRepository->save($daysOff, true);

            return $this->redirectToRoute('daysOff_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_off/new.html.twig', [
            'days_off' => $daysOff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(DaysOff $daysOff): Response
    {
        return $this->render('days_off/show.html.twig', [
            'days_off' => $daysOff,
        ]);
    }

    #[Route('/{id}/modifier', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DaysOff $daysOff, DaysOffRepository $daysOffRepository): Response
    {
        $form = $this->createForm(DaysOffType::class, $daysOff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $daysOffRepository->save($daysOff, true);

            return $this->redirectToRoute('daysOff_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_off/edit.html.twig', [
            'days_off' => $daysOff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, DaysOff $daysOff, DaysOffRepository $daysOffRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $daysOff->getId(), $request->request->get('_token'))) {
            $daysOffRepository->remove($daysOff, true);
        }

        return $this->redirectToRoute('daysOff_index', [], Response::HTTP_SEE_OTHER);
    }
}
