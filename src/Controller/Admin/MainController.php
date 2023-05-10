<?php

namespace App\Controller\Admin;

use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Repository\AppointmentsRepository;
use App\Repository\UsersRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AppointmentsRepository $appointmentsRepository, UsersRepository $usersRepository): Response
    {
        // Je vérifie que l'utilisateur est bien connecté
        // et qu'il a le rôle: ROLE_ADMIN
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // $user = $this->getUser();
        // return $this->render('admin/index.html.twig', compact('user'));

        // Je crée un nouveau rendez-vous
        $appointment = new Appointments();
        // // Je crée un formulaire pour ajouter un rendez-vous
        // $appointmentForm = $this->createForm(AppointmentsType::class, $appointment);

        // Je récupère tous les rendez-vous
        $appointments = $appointmentsRepository->findAll();
        // J'initialise un tableau vide qui va contenir les données formatées
        $rdvs = [];
        // Les données doivent être formatées en JSON pour être utilisées par FullCalendar
        // Pour les noms des clés, il faut utiliser ceux de la documentation de FullCalendar
        foreach ($appointments as $appointment) {
            // Je récupère la durée du soin pour calculer l'heure de fin
            $day = $appointment->getDate()->format('Y-m-d');
            $duration = $appointment->getCare()->getDuration()->format('i:s');
            // Pour couper une chaine de caractères, on utilise substr()
            $duration = substr($duration, 0, 2);
            // Je récupère l'heure du rendez-vous
            $time = $appointment->getDate()->format('H:i:s');
            // Je crée un objet DateTime avec la date et l'heure du rendez-vous
            $date = new DateTime($day . '' . $time);
            $start = $date->format('Y-m-d H:i:s');
            // J'ajoute la durée du soin à l'heure de début pour avoir l'heure de fin
            $end = $date->modify('+' . $duration . ' minutes');
            $end = $end->format('Y-m-d H:i:s');
            // Je récupère le nom du patient
            $userId = $appointment->getUserId();
            // dd($userId);
            $user = $usersRepository->find($userId);
            $user = $user->getFirstname() . ' ' . $user->getLastname();
            // Je récupère l'intitulé du soin
            $care = $appointment->getCare()->getName();
            $title = $user . ' - ' . $care;
            // Je récupère la couleur du soin
            $color = $appointment->getCare()->getColor();

            $rdvs[] = [
                'id' => $appointment->getId(),
                'start' => $start,
                'end' => $end,
                'title' => $title,
                'color' => $color,
                'textColor' => '#000',
            ];
        }
        // Je retourne les données formatées en JSON
        $data = json_encode($rdvs);

        // Je crée un formulaire pour la modale
        $appointmentForm = $this->createForm(AppointmentsType::class, $appointment);

        return $this->render('admin/index.html.twig', [
            'data' => $data,
            'appointmentForm' => $appointmentForm->createView(),
        ]);
    }
}
