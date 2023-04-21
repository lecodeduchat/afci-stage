<?php

namespace App\Controller;

use App\Repository\AppointmentsRepository;
use App\Repository\CaresRepository;
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

    #[Route('/rendez-vous', name: 'appointments')]
    public function appointments(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Rendez-vous de l\'utilisateur',
        ]);
    }
}
