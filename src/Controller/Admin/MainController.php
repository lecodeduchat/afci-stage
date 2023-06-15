<?php

namespace App\Controller\Admin;

use DateTime;
use App\Classes\Calendar;
use App\Entity\Appointments;
use App\Form\AdminAppointmentsType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\DaysOnRepository;
use App\Repository\DaysOffRepository;
use App\Repository\AppointmentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, AppointmentsRepository $appointmentsRepository, UsersRepository $usersRepository, DaysOnRepository $daysOnRepository, DaysOffRepository $daysOffRepository, CaresRepository $caresRepository): Response
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
        $rdvs = new Calendar($appointmentsRepository, $daysOnRepository, $daysOffRepository, $usersRepository);
        $rdvs = $rdvs->getAppointments();
        // Je récupère les heures non travaillées
        $hoursOff = new Calendar($appointmentsRepository, $daysOnRepository, $daysOffRepository, $usersRepository);
        $hoursOff = $hoursOff->getHoursOff();
        // dd($hoursOff);
        // Je fusionne les tableaux des rendez-vous et des heures non travaillées
        $data = array_merge($rdvs, $hoursOff);
        ksort($data);
        // Une fois les deux tableaux fusionnés et triés, je les replace dans un tableau sans clé pour pouvoir les utiliser dans le calendrier
        $datas = [];
        foreach ($data as $elt) {
            $datas[] = $elt;
        }
        // dd($datas);
        $datas = json_encode($datas);

        return $this->render('admin/index.html.twig', [
            'data' => $datas,
            'cares' => $caresRepository->findAll(),
        ]);
    }
}
