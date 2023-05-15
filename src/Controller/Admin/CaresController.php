<?php

namespace App\Controller\Admin;

use App\Entity\Cares;
use App\Form\CaresType;
use App\Repository\CaresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/seances',  name: 'cares_')]
class CaresController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CaresRepository $caresRepository): Response
    {
        return $this->render('admin/cares/index.html.twig', [
            'cares' => $caresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, CaresRepository $caresRepository): Response
    {
        $care = new Cares();
        $form = $this->createForm(CaresType::class, $care);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caresRepository->save($care, true);

            return $this->redirectToRoute('app_cares_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/cares/new.html.twig', [
            'care' => $care,
            'Caresform' => $form,
        ]);
    }

    #[Route('show/{id}', name: 'show', methods: ['GET'])]
    public function show(Cares $care): Response
    {
        return $this->render('admin/cares/show.html.twig', [
            'care' => $care,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cares $care, CaresRepository $caresRepository): Response
    {
        $form = $this->createForm(CaresType::class, $care);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caresRepository->save($care, true);

            return $this->redirectToRoute('cares_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/cares/edit.html.twig', [
            'care' => $care,
            'Caresform' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'GET', 'DELETE'])]
    public function delete(Request $request, Cares $care, CaresRepository $caresRepository): Response
    {
        echo ('test');
        if ($this->isCsrfTokenValid('delete' . $care->getId(), $request->request->get('_token'))) {

            $caresRepository->remove($care, true);
        }

        return $this->redirectToRoute('cares_index', [], Response::HTTP_SEE_OTHER);
    }
}
