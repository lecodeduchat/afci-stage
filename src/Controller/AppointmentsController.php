<?php

namespace App\Controller;

use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Repository\AppointmentsRepository;
use App\Repository\CaresRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rendez-vous', name: 'appointments_')]
class AppointmentsController extends AbstractController
{
    private $days = [
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche',
    ];
    private $months = [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Août',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre'
    ];



    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository): Response
    {
        return $this->render('appointments/index.html.twig', [
            'appointments' => $appointmentsRepository->findAll(),
            'firstCares' => $caresRepository->findByExampleField('Première%'),
            'secondCares' => $caresRepository->findByExampleField('Suivi%'),
        ]);
    }
    #[Route('/{id}', name: 'slots', methods: ['GET'])]
    public function slots(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository): Response
    {
        // Je récupère la date du jour
        $date = new \DateTime();
        // Je la formate pour la passer en paramètre à la requête
        $date = $date->format('Y-m-d');
        $appointments = $appointmentsRepository->findAllSince($date);
        // Création d'un timestamp à 9h00 de la date du jour
        $time = mktime(9, 0, 0, date('m'), date('d'), date('Y'));
        // dd(date("H:i", $time));
        return $this->render('appointments/slots.html.twig', [
            'appointments' => $appointments,
            'cares' => $caresRepository->findAll(),
            'id' => 'id',
            'days' => $this->days,
            'months' => $this->months,
            'time' => $time,
        ]);
    }

    #[Route('/new', name: 'app_appointments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AppointmentsRepository $appointmentsRepository): Response
    {
        $appointment = new Appointments();
        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentsRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointments/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointments_show', methods: ['GET'])]
    public function show(Appointments $appointment): Response
    {
        return $this->render('appointments/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointments $appointment, AppointmentsRepository $appointmentsRepository): Response
    {
        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentsRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointments/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointments_delete', methods: ['POST'])]
    public function delete(Request $request, Appointments $appointment, AppointmentsRepository $appointmentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $appointmentsRepository->remove($appointment, true);
        }

        return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
    }
}
