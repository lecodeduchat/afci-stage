<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Appointments;
use App\Form\AdminAppointmentsType;
use App\Service\SendMailService;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\DaysOnRepository;
use App\Repository\AppointmentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, AppointmentsRepository $appointmentsRepository, UsersRepository $usersRepository, DaysOnRepository $daysOnRepository, SendMailService $mail, CaresRepository $caresRepository): Response
    {
        // Je vérifie que l'utilisateur est bien connecté
        if (!$this->getUser()) {
            // Si non, je le redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }
        // Je vérifie qu'il a le rôle: ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si non, je le redirige vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Je récupère la date du jour
        $today = new DateTime();

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
        // Je colore les heures non travaillées
        // Je récupère les jours ouvrés
        $daysOn = $daysOnRepository->findAllSince($today);
        foreach ($daysOn as $dayOn) {
            // Je récupère la date du jour
            $day = $dayOn->getDate()->format('Y-m-d');

            // Je récupère l'heure de début de la journée
            $startMorning = $dayOn->getStartMorning();
            $endMorning = $dayOn->getEndMorning();
            $startAfternoon = $dayOn->getStartAfternoon();
            $endAfternoon = $dayOn->getEndAfternoon();

            if ($startMorning != null) {
                $startMorning = $startMorning->format('H:i:s');
                // Je colore les heures non travaillées du matin
                $rdvs[] = [
                    'id' => 0,
                    'start' => $day . ' 08:30:00',
                    'end' => $day . ' ' . $startMorning,
                    'title' => 'Heures non travaillées',
                    'color' => '#ddd',
                    'textColor' => '#000',
                ];
            }
            if ($endMorning != null && $startAfternoon != null) {
                $endMorning = $endMorning->format('H:i:s');
                $startAfternoon = $startAfternoon->format('H:i:s');
                // Je colore les heures non travaillées du midi
                $rdvs[] = [
                    'id' => 0,
                    'start' => $day . ' ' . $endMorning,
                    'end' => $day . ' ' . $startAfternoon,
                    'title' => 'Pause repas',
                    'color' => '#ddd',
                    'textColor' => '#000',
                ];
            }
            if ($endAfternoon != null) {
                $endAfternoon = $endAfternoon->format('H:i:s');
                // Je colore les heures non travaillées de l'après-midi
                $rdvs[] = [
                    'id' => 0,
                    'start' => $day . ' ' . $endAfternoon,
                    'end' => $day . ' 20:00:00',
                    'title' => 'Heures non travaillées',
                    'color' => '#ddd',
                    'textColor' => '#000',
                ];
            }
            if ($startMorning == null || $startMorning == "00:00:00") {
                $rdvs[] = [
                    'id' => 0,
                    'start' => $day . ' 08:30:00',
                    'end' => $day . ' 13:00:00',
                    'title' => 'Matinée OFF',
                    'color' => '#ddd',
                    'textColor' => '#000',
                ];
            }
            if ($startAfternoon == null || $startAfternoon == "00:00:00") {
                $rdvs[] = [
                    'id' => 0,
                    'start' => $day . ' 14:00:00',
                    'end' => $day . ' 20:00:00',
                    'title' => 'Après-midi OFF',
                    'color' => '#ddd',
                    'textColor' => '#000',
                ];
            }
        }

        // Je retourne les données formatées en JSON
        $data = json_encode($rdvs);

        return $this->render('admin/index.html.twig', [
            'data' => $data,
            'cares' => $caresRepository->findAll(),
        ]);
    }
}
