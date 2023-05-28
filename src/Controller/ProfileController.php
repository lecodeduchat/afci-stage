<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\ChildsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// On définit manuellement le nom de la route depuis laquelle on accèdera à toutes les méthodes de ce contrôleur
#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository, UsersRepository $usersRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        // Je récupère la date du jour
        $date = new \DateTime();
        //! TODO: Mystère à résoudre ma requête avec >= ne fonctionne pas !!! pour nextAppointments
        // Je retire 1 jour à la date du jour car bug avec la date du jour
        $date->modify('-1 day');

        // Je vérifie si l'utilisateur a des enfants
        $childs = $usersRepository->findChildsByUser($user->getId(), '["ROLE_CHILD"]');

        // Je récupère l'historique des rendez-vous de l'utilisateur connecté
        $oldAppointments = $appointmentsRepository->findOldAppointmentByUser($user->getId(), $date);
        // dd($oldAppointments);
        $pagination = $paginator->paginate(
            $appointmentsRepository->paginationQueryAppointmentsUser($user->getId(), $date),
            // je recupère la page et par defaut je lui met la 1
            $request->query->get('page', 1),
            10
        );

        // Je récupère les rendez-vous à venir de l'utilisateur connecté
        $nextAppointments = $appointmentsRepository->findNextAppointmentByUser($user->getId(), $date);

        // Je récupère tous les soins
        $cares = $caresRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'pagination' => $pagination,
            'nextAppointments' => $nextAppointments,
            'cares' => $cares,
            'childs' => $childs,
        ]);
    }

    #[Route('/votre-profil', name: 'show')]
    public function show(UsersRepository $usersRepository): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        // Je vérifie si l'utilisateur a des enfants
        $childs = $usersRepository->findChildsByUser($user->getId(), '["ROLE_CHILD"]');
        dd($childs);
        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'childs' => $childs,
        ]);
    }

    #[Route('/votre-profil/modifier', name: 'edit')]
    public function update(Request $request, UsersRepository $usersRepository): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        // Je récupère le formulaire de modification de profil en réutilisant le formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepository->update($user, true);
            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/votre-profil/changer-mot-de-passe', name: 'changePassword')]
    public function changePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        if ($user) {

            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('profile_index');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView(),
                'user' => $user
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('profile_index');
    }
}
