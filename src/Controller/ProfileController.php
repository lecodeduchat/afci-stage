<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\AppointmentsRepository;
use App\Repository\ChildsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// On définit manuellement le nom de la route depuis laquelle on accèdera à toutes les méthodes de ce contrôleur
#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository, ChildsRepository $childsRepository): Response
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
        $childs = $childsRepository->findByUser($user);
        // dd($user->getId());
        // Je récupère l'historique des rendez-vous de l'utilisateur connecté
        $oldsAppointments = $appointmentsRepository->findOldAppointmentByUser($user->getId(), $date);

        // Je récupère les rendez-vous à venir de l'utilisateur connecté
        $nextAppointments = $appointmentsRepository->findNextAppointmentByUser($user->getId(), $date);

        // Je récupère tous les soins
        $cares = $caresRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'oldsAppointments' => $oldsAppointments,
            'nextAppointments' => $nextAppointments,
            'cares' => $cares,
            'childs' => $childs,
        ]);
    }

    #[Route('/votre-profil', name: 'show')]
    public function show(ChildsRepository $childsRepository): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        // Je vérifie si l'utilisateur a des enfants
        $childs = $childsRepository->findByUser($user);

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
}
