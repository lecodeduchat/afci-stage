<?php

namespace App\Controller;

use App\Entity\Childs;
use App\Form\ChildsType;
use App\Repository\ChildsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enfants')]
class ChildsController extends AbstractController
{
    #[Route('/', name: 'app_childs_index', methods: ['GET'])]
    public function index(ChildsRepository $childsRepository): Response
    {
        $user = "";
        if ($this->getUser()) {
            $user = $this->getUser();
        }
        return $this->render('childs/index.html.twig', [
            'childs' => $childsRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_childs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChildsRepository $childsRepository): Response
    {
        $child = new Childs();
        $form = $this->createForm(ChildsType::class, $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $childsRepository->save($child, true);

            return $this->redirectToRoute('app_childs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('childs/new.html.twig', [
            'child' => $child,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_childs_show', methods: ['GET'])]
    public function show(Childs $child): Response
    {
        return $this->render('childs/show.html.twig', [
            'child' => $child,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_childs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Childs $child, ChildsRepository $childsRepository): Response
    {
        $form = $this->createForm(ChildsType::class, $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $childsRepository->save($child, true);

            return $this->redirectToRoute('app_childs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('childs/edit.html.twig', [
            'child' => $child,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_childs_delete', methods: ['POST'])]
    public function delete(Request $request, Childs $child, ChildsRepository $childsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $child->getId(), $request->request->get('_token'))) {
            $childsRepository->remove($child, true);
        }

        return $this->redirectToRoute('app_childs_index', [], Response::HTTP_SEE_OTHER);
    }
}
