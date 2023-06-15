<?php

namespace App\Controller;

use App\Classes\Slots;
use App\Entity\Childs;
use App\Form\ChildsType;
use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Service\SendMailService;
use App\Repository\CaresRepository;
use App\Repository\ChildsRepository;
use App\Repository\AppointmentsRepository;
use App\Repository\DaysOffRepository;
use App\Repository\DaysOnRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/rendez-vous', name: 'appointments_')]
class AppointmentsController extends AbstractController
{
    private $days = [
        'lundi',
        'mardi',
        'mercredi',
        'jeudi',
        'vendredi',
        'samedi',
        'dimanche',
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
        $user = "";
        if ($this->getUser()) {
            $user = $this->getUser();
        }
        return $this->render('appointments/index.html.twig', [
            'appointments' => $appointmentsRepository->findAll(),
            'firstCares' => $caresRepository->findByExampleField('Première%'),
            'secondCares' => $caresRepository->findByExampleField('Suivi%'),
            'cares' => $caresRepository->findAll(),
            'user' => $user,
        ]);
    }
    #[Route('/slots/{slug}', name: 'slots', methods: ['GET'])]
    public function slots(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository, DaysOnRepository $daysOnRepository, DaysOffRepository $daysOffRepository, Request $request): Response
    {
        $user = "";
        if ($this->getUser()) {
            $user = $this->getUser();
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
        // Création d'un tableau de créneaux horaires
        $slots = new Slots($appointmentsRepository, $daysOnRepository, $daysOffRepository, $careDuraton);
        $slots = $slots->getSlots();

        // Création d'un timestamp à 9h00 de la date du jour
        $time = mktime(9, 0, 0, date('m'), date('d'), date('Y'));

        return $this->render('appointments/slots.html.twig', [
            'slots' => $slots,
            'cares' => $caresRepository->findAll(),
            'days' => $this->days,
            'months' => $this->months,
            'time' => $time,
            'user' => $user,
        ]);
    }

    #[Route('/enfants', name: 'childs', methods: ['GET', 'POST'])]
    public function childs(Request $request, ChildsRepository $childsRepository, CaresRepository $caresRepository): Response
    {
        // Je mémorise la page sur laquelle l'utilisateur se trouve pour le rediriger après la connexion
        $session = $request->getSession();
        $session->set('redirect', '/rendez-vous/enfants');

        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $childs = $childsRepository->findByUser($user);

        // Je crée un nouveau formulaire pour ajouter un enfant
        $child = new Childs();
        $child->setParent1($user);

        //! La méthode getLastname est soulignée mais elle fonctionne
        $nom = $user->getLastname();
        $child->setLastname($nom);
        $childForm = $this->createForm(ChildsType::class, $child);
        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $child->setParent2(NULL);
            $childsRepository->save($child, true);
            // TODO : Ajouter un message flash indiquant que l'enfant a bien été ajouté
            return $this->redirectToRoute('appointments_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointments/childs.html.twig', [
            'childs' => $childs,
            'user' => $user,
            'cares' => $caresRepository->findAll(),
            'childForm' => $childForm->createView(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        AppointmentsRepository $appointmentsRepository,
        CaresRepository $caresRepository,
        SendMailService $mail,
        ChildsRepository $childsRepository
    ): Response {
        // Je mémorise la page sur laquelle l'utilisateur se trouve pour le rediriger après la connexion
        $session = $request->getSession();
        $session->set('redirect', '/rendez-vous/new');
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Je récupère l'utilisateur connecté
        $user = $this->getUser();
        // Je crée un nouveau rendez-vous
        $appointment = new Appointments();
        // Je récupère l'utilisateur connecté et je l'associe au rendez-vous
        $appointment->setUserId($user->getId());
        // Je récupère le dernier enfant ajouté par l'utilisateur et je vérifirai si il correspond à l'enfant sélectionné dans le formulaire
        $lastChild = $childsRepository->findLastChildByUser($user);
        if ($lastChild == NULL) {
            $lastChildId = "";
            $firstnameLastChild = "";
        } else {
            $lastChildId = $lastChild[0]->getId();
            $firstnameLastChild = $lastChild[0]->getFirstname();
        }
        // Je récupère la liste des enfants
        $childs = $childsRepository->findByUser($user);
        $enfants = [];
        foreach ($childs as $child) {
            $enfants[$child->getId()] = [
                'firstname' => $child->getFirstname(),
            ];
        }
        $childs = json_encode($enfants);

        // Je récupère la liste des soins
        $cares = $caresRepository->findAll();
        $soins = [];
        foreach ($cares as $care) {
            $soins[$care->getId()] = [
                'id' => $care->getId(),
                'name' => $care->getName(),
                'duration' => $care->getDuration()->format('H:i'),
                'price' => $care->getPrice(),
            ];
        }
        $cares = json_encode($soins);

        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {

            $appointmentsRepository->save($appointment, true);
            // Ajoute d'un message flash indiquant que le rendez-vous a bien été créé
            $this->addFlash('success', 'Votre rendez-vous a été pris en compte. Vous allez recevoir un email de confirmation.');
            // Envoi d'un email de confirmation de rendez-vous

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
                'Email de confirmation de votre rendez vous',
                'rendezvousclient',
                compact('user', 'appointment')
            );

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointments/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'cares' => $cares,
            'user' => $user,
            'lastChildId' => $lastChildId,
            'firstnameLastChild' => $firstnameLastChild,
            'childs' => $childs,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Appointments $appointment): Response
    {
        return $this->render('appointments/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointments $appointment, AppointmentsRepository $appointmentsRepository): Response
    {
        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentsRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointments/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'GET', 'DELETE'])]
    public function delete(Request $request, Appointments $appointment, AppointmentsRepository $appointmentsRepository, SendMailService $mail): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $appointmentsRepository->remove($appointment, true);

            $user = $this->getUser();

            $this->addFlash('success', 'Votre rendez-vous a été annulé');

            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Annulation de votre rendez vous',
                'annulation',
                compact('user', 'appointment')
            );
            $mail->send(
                'no-reply@monsite.net',
                'no-reply@monsite.net',
                'Annulation rendez vous',
                'annulationclient',
                compact('user', 'appointment')
            );
        }

        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
