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
    #[Route('/new', name: 'app_childs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChildsRepository $childsRepository): Response
    {
        $user = $this->getUser();
        $lastname = $user->getLastname();
        $child = new Childs();
        $child->setParent1($user);
        $child->setLastname($lastname);
        $childForm = $this->createForm(ChildsType::class, $child);
        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $childsRepository->save($child, true);

            return $this->redirectToRoute('profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('childs/new.html.twig', [
            'child' => $child,
            'childForm' => $childForm,
            'user' => $user,
        ]);
    }

    #[Route('/edition/{id}', name: 'app_childs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Childs $child, ChildsRepository $childsRepository): Response
    {
        $form = $this->createForm(ChildsType::class, $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $childsRepository->save($child, true);

            return $this->redirectToRoute('app_childs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('childs/edit.html.twig', [
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
