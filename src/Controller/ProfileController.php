<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersFormType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\AppointmentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// On définit manuellement le nom de la route depuis laquelle on accèdera à toutes les méthodes de ce contrôleur
#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        // Je récupère la date du jour
        $date = new \DateTime();
        // Je récupère l'historique des rendez-vous de l'utilisateur connecté
        $oldsAppointments = $appointmentsRepository->findOldAppointmentByUser($user, $date);
        // Je récupère les rendez-vous à venir de l'utilisateur connecté
        $nextAppointments = $appointmentsRepository->findNextAppointmentByUser($user, $date);
        // Je récupère tous les soins
        $cares = $caresRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'oldsAppointments' => $oldsAppointments,
            'nextAppointments' => $nextAppointments,
            'cares' => $cares,
        ]);
    }

    #[Route('/votre-profil', name: 'show')]
    public function show(): Response
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
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

        $form = $this->createForm(UsersFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepository->update($user, true);
            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
