<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Classes\Slots;
use App\Entity\Childs;
use App\Entity\Appointments;
use App\Form\UsersFormType;
use App\Form\ChildsType;
use App\Service\SendMailService;
use App\Form\AdminAppointmentsType;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\ChildsRepository;
use App\Repository\DaysOnRepository;
use App\Repository\AppointmentsRepository;
use App\Repository\DaysOffRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/rendez-vous', name: 'admin_appointments_')]
class AppointmentsController extends AbstractController
{
    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        AppointmentsRepository $appointmentsRepository,
        DaysOnRepository $daysOnRepository,
        DaysOffRepository $daysOffRepository,
        UsersRepository $userRepository,
        CaresRepository $caresRepository,
        SendMailService $mail,
        ChildsRepository $childsRepository,
    ): Response {

        // Initialisation d'un patient
        $user = new Users();
        $userForm = $this->createForm(UsersFormType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userRepository->save($user, true);
            // Affichage d'un message flash à l'utilisateur
            $this->addFlash('success', 'Le patient a été ajouté avec succès.');
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        // Durée du soin choisi en minutes
        $slug = $request->attributes->get('slug');
        if ($slug == "premiere-consultation") {
            $careDuraton = 45;
        } elseif ($slug == "suivi-consultation") {
            $careDuraton = 30;
        } else {
            // erreur de slug : je redirige vers la page appointments_index
            return $this->redirectToRoute('appointments_index');
        }

        // Initialisation d'un enfant
        $child = new Childs();
        $childForm = $this->createForm(ChildsType::class, $child);
        $childForm->handleRequest($request);
        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $childsRepository->save($child, true);
            // Affichage d'un message flash à l'utilisateur
            $this->addFlash('success', 'L\'enfant a été ajouté avec succès.');
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        // Je récupère tous les clients
        $users = $userRepository->findAllUsers();
        $clients = [];
        $cpt = 0;
        foreach ($users as $user) {
            $clients[$cpt] = [
                "id" => $user->getId(),
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
                "cellphone" => $user->getCellPhone(),
                "email" => $user->getEmail(),
            ];
            $cpt++;
        }

        // Création d'un tableau de créneaux horaires

        $slots = new Slots($appointmentsRepository, $daysOnRepository, $daysOffRepository, $careDuraton);

        $slots = $slots->getSlots();
        // dd($slots);
        // Initialisation d'un nouveau rendez-vous
        $appointment = new Appointments();
        // Formulaire de prise de rendez-vous
        $appointmentForm = $this->createForm(AdminAppointmentsType::class, $appointment);
        $appointmentForm->handleRequest($request);

        if ($appointmentForm->isSubmitted() && $appointmentForm->isValid()) {

            // TODO : Vérifier que le rendez-vous n'est pas déjà pris (si code javascript modifié par un hacker) ou pris entre temps par un autre utilisateur
            $appointmentsRepository->save($appointment, true);
            // Affichage d'un message flash à l'utilisateur
            $this->addFlash('success', 'Le rendez-vous a été pris en compte. Vous allez recevoir un email de confirmation.');
            // Envoye d'un mail de confirmation à l'utilisateur
            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Email de confirmation de votre rendez vous',
                'rendezvous',
                compact('user', 'appointment')
            );
            $mail->send(
                'no-reply@monsite.net',
                'no-reply@monsite.net',
                'Email de confirmation d\'un rendez vous',
                'rendezvousclient',
                compact('user', 'appointment')
            );

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/appointments/new.html.twig', [
            'appointment' => $appointment,
            'appointmentForm' => $appointmentForm,
            'userForm' => $userForm,
            'childForm' => $childForm,
            'cares' => $caresRepository->findAll(),
            'slots' => $slots,
            'months' => Slots::MONTHS,
            'clients' => $clients,
        ]);
    }
}
