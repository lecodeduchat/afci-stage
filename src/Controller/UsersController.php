<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersChildsFormType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/patients', name: 'app_users')]
class UsersController extends AbstractController
{
    #[Route('/nouveau', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();
        $lastname = $user->getLastname();
        $child = new Users();
        $child->setRoles(["ROLE_CHILD"]);
        $child->setUserRef($user->getId());
        $child->setLastname($lastname);
        $childForm = $this->createForm(UsersChildsFormType::class, $child);
        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $usersRepository->save($child, true);

            return $this->redirectToRoute('profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/new.html.twig', [
            'child' => $child,
            'childForm' => $childForm,
            'user' => $user,
        ]);
    }

    #[Route('/edition/{id}', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UsersRepository $usersRepository): Response
    {

        $childId = $request->attributes->get('id');
        $child = $usersRepository->find($childId);

        $childForm = $this->createForm(UsersChildsFormType::class, $child);
        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $usersRepository->save($child, true);

            return $this->redirectToRoute('profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/edit.html.twig', [
            'child' => $child,
            'childForm' => $childForm,
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
