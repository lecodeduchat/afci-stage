<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Classes\Slots;
use App\Entity\Childs;
use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Service\SendMailService;
use App\Repository\CaresRepository;
use App\Repository\UsersRepository;
use App\Repository\ChildsRepository;
use App\Repository\DaysOnRepository;
use App\Repository\CustomersRepository;
use App\Repository\AppointmentsRepository;
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
        UsersRepository $userRepository,
        CaresRepository $caresRepository,
        SendMailService $mail,
        ChildsRepository $childsRepository,
        CustomersRepository $customersRepository
    ): Response {
        // Initialisation d'un patient
        $user = new Users();
        // Initialisation d'un enfant
        $child = new Childs();
        // Patients déjà inscrits
        $users = $userRepository->findAllUsers();
        // Patients non inscrits
        $customers = $customersRepository->findAllCustomers();
        // Création de la liste des clients
        $clients = [];
        foreach ($users as $user) {
            $name = $user->getLastname() . ' ' . $user->getFirstname();
            $clients[$name]["type"] = "user";
            $clients[$name]["id"] = $user->getId();
        }
        foreach ($customers as $customer) {
            $name = $customer->getFirstname();
            $clients[$name]["type"] = "customer";
            $clients[$name]["id"] = $customer->getId();
        }
        ksort($clients);
        // dd($clients['AGNES LECOUTRE']["customerId"]);
        // Création d'un tableau de créneaux horaires
        $slots = new Slots($appointmentsRepository, $daysOnRepository);
        $slots = $slots->getSlots();
        // dd($slots);
        // Initialisation d'un nouveau rendez-vous
        $appointment = new Appointments();
        // Formulaire de prise de rendez-vous
        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            'form' => $form,
            'users' => $users,
            'cares' => $caresRepository->findAll(),
            'slots' => $slots,
            'months' => Slots::MONTHS,
            'customers' => $customers,
            'users' => $users,
            'clients' => $clients,
        ]);
    }
}
