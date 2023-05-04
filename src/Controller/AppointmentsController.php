<?php

namespace App\Controller;

use DateTime;
use App\Entity\Users;
use App\Entity\Childs;
use App\Entity\Holidays;
use App\Form\ChildsType;
use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Service\SendMailService;
use App\Repository\CaresRepository;
use App\Repository\ChildsRepository;
use App\Repository\HolidaysRepository;
use App\Repository\SchedulesRepository;
use App\Repository\VacationsRepository;
use App\Repository\AppointmentsRepository;
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
    #[Route('/slots', name: 'slots', methods: ['GET'])]
    public function slots(AppointmentsRepository $appointmentsRepository, CaresRepository $caresRepository, SchedulesRepository $schedulesRepository, HolidaysRepository $holidaysRepository, VacationsRepository $vacationsRepository): Response
    {
        $user = "";
        if ($this->getUser()) {
            $user = $this->getUser();
        }
        // Je récupère la date du jour
        $date = new \DateTime();
        $timeNow = $date;
        // Je la formate pour la passer en paramètre à la requête
        $date = $date->format('Y-m-d');
        $appointments = $appointmentsRepository->findAllSince($date);
        $schedules = $schedulesRepository->findAll();
        // Je crée un tableau pour stocker les créneaux horaires de chaque jour
        $slots = [];

        // Je crée une fonction pour vérifier si le jour est un jour ouvré (hors férié, vacances et dimanche)
        function isWorkingDay($date, $vacationsRepository, $holidaysRepository)
        {
            $vacation = $vacationsRepository->findByDate($date);
            $holiday = $holidaysRepository->findByDate($date);
            $numDay = date('N', strtotime($date));
            if ($vacation || $holiday || $numDay == 7) {
                return false;
            } else {
                return true;
            }
        }
        for ($i = 0; $i < 15; $i++) {
            $result = isWorkingDay($date, $vacationsRepository, $holidaysRepository);
            // Test limiter à 5 semaines complètes donc 35 jours pour éviter une boucle trop longue
            $cpt = 0;
            while (!$result) {
                $date = date('Y-m-d', strtotime($date . ' + 1 days'));
                $result = isWorkingDay($date, $vacationsRepository, $holidaysRepository);
                $cpt++;
                if ($cpt > 35) {
                    break;
                }
            }
            // Je crée une date pour chaque journée de rendez-vous
            $slots[$i]["date"] = $date;
            // Je récupère le numéro du jour de la semaine
            $numDay = date('N', strtotime($date));
            // Je récupère le nom du jour de la semaine en retirant 1 au numéro du jour car le tableau commence à l'indice 0
            $nameday = $this->days[$numDay - 1];
            // Je stocke le nom du jour dans le tableau
            $slots[$i]["day"] = $nameday;
            // Je crée un tableau pour stocker les créneaux horaires de chaque jour
            $slots[$i]["slots"] = [];
            // Je parcours les créneaux horaires de chaque jour pour récupérer l'amplitude horaire de chaque jour
            foreach ($schedules as $schedule) {
                if ($schedule->getDay() == $nameday) {
                    $morningStart = $schedule->getMorningStart()->format("H:i");
                    $morningEnd = $schedule->getMorningEnd()->format("H:i");
                    $afternoonStart = $schedule->getAfternoonStart()->format("H:i");
                    $afternoonEnd = $schedule->getAfternoonEnd()->format("H:i");
                    // Je fixe la limite de créneaux horaires pour la matinée et l'après-midi en me basant sur des créneaux de 30 minutes
                    //! Problème : si la limite est fixée à 12h30, le dernier créneau horaire sera à 12h00 mais pour un premier rendez-vous cela repousse la fin de matinée à 12h45 !!!
                    $limitMorning = strtotime($morningEnd) - 30 * 60;
                    $limitAfternoon = strtotime($afternoonEnd) - 30 * 60;
                    // Je crée la variable $slot, je lui attribut la valeur du début de matinée sauf si le jour est aujourd'hui et que l'heure actuelle est supérieure à l'heure de début de matinée
                    if ($i == 0 && $timeNow > $schedule->getMorningStart()) {
                        $hour = $timeNow->format("H");
                        $minutes = $timeNow->format("i");
                        if ($minutes < 30) {
                            $slot = mktime($hour, 30, 0, date('m'), date('d'), date('Y'));;
                        } else {
                            $hour = $hour + 1;
                            $slot = mktime($hour, 0, 0, date('m'), date('d'), date('Y'));;
                        }
                    } else {
                        $slot = strtotime($morningStart);
                    }
                    // dd($slot);
                    // Je recherche dans la liste des rendez-vous, ceux qui correspondent à la date du jour 
                    foreach ($appointments as $appointment) {
                        if ($appointment->getDate()->format('Y-m-d') == $date) {
                            // Si un rendez-vous correspond à la date du jour
                            // Je récupère l'heure du rendez-vous
                            $time = strtotime($appointment->getTime()->format("H:i"));
                            // dd($appointment->getTime()->format("H:i"));
                            // Je récupère la durée du rendez-vous
                            $duration = $appointment->getCare()->getDuration()->format("i");
                            // Je compare l'heure du rendez-vous avec l'heure du créneau horaire
                            while ($slot < $time) {
                                // Si l'heure du créneau horaire est inférieure à la limite de matinée
                                if ($slot < $limitMorning) {
                                    $horaire = date("H:i", $slot);
                                    array_push($slots[$i]["slots"], $horaire);
                                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                                    $slot = $slot + 30 * 60;
                                } elseif ($slot >= $limitMorning && $slot < strtotime($afternoonStart)) {
                                    if ($slot == $limitMorning) {
                                        $horaire = date("H:i", $slot);
                                        array_push($slots[$i]["slots"], $horaire);
                                    }
                                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                                    $slot = strtotime($afternoonStart);
                                } else {
                                    $horaire = date("H:i", $slot);
                                    array_push($slots[$i]["slots"], $horaire);
                                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                                    $slot = $slot + 30 * 60;
                                }
                            }
                            if ($slot == $time) {
                                $slot = $slot + $duration * 60;
                            }
                        }
                    }
                    // Si aucun rendez-vous ne correspond à la date du jour
                    // Ou si $slot > $limitAfternoon
                    while ($slot <= $limitAfternoon) {
                        if ($slot < $limitMorning) {
                            $horaire = date("H:i", $slot);
                            array_push($slots[$i]["slots"], $horaire);
                            // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                            $slot = $slot + 30 * 60;
                        } elseif ($slot >= $limitMorning && $slot < strtotime($afternoonStart)) {
                            $horaire = date("H:i", $slot);
                            array_push($slots[$i]["slots"], $horaire);
                            // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                            $slot = strtotime($afternoonStart);
                        } else {
                            $horaire = date("H:i", $slot);
                            array_push($slots[$i]["slots"], $horaire);
                            // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                            $slot = $slot + 30 * 60;
                        }
                    }
                }
            }
            // Je décale la date du jour de 1 jour
            $date = date("Y-m-d", strtotime($date . "+1 day"));
        }
        // dd($slots);
        // Création d'un timestamp à 9h00 de la date du jour
        $time = mktime(9, 0, 0, date('m'), date('d'), date('Y'));
        // dd(date("H:i", $time));
        return $this->render('appointments/slots.html.twig', [
            'appointments' => $appointments,
            'slots' => $slots,
            'cares' => $caresRepository->findAll(),
            'days' => $this->days,
            'months' => $this->months,
            'time' => $time,
            'user' => $user,
        ]);
    }
    #[Route('/etape', name: 'step', methods: ['GET', 'POST'])]
    public function testCare()
    {
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
    }

    #[Route('/enfants', name: 'childs', methods: ['GET', 'POST'])]
    public function childs(Request $request, ChildsRepository $childsRepository, CaresRepository $caresRepository): Response
    {
        $session = $request->getSession();
        $session->set('redirect', '/rendez-vous/enfants');

        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $childs = $childsRepository->findByUser(['user' => $user]);

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
        // Je vérifie que l'utilisateur est connecté , sinon je le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        // Je crée un nouveau rendez-vous
        $appointment = new Appointments();
        // Je récupère l'utilisateur connecté et je l'associe au rendez-vous
        $appointment->setUser($user);
        // Je récupère le dernier enfant ajouté par l'utilisateur et je vérifirai si il correspond à l'enfant sélectionné dans le formulaire
        $lastChild = $childsRepository->findLastChildByUser($user);
        $lastChildId = $lastChild[0]->getId();
        $firstnameLastChild = $lastChild[0]->getFirstname();
        // Je récupère la liste des enfants
        $childs = $childsRepository->findByUser($user);
        $enfants = [];
        // foreach ($childs as $child) {
        //     $enfants[] = [
        //         'id' => $child->getId(),
        //         'firstname' => $child->getFirstname(),
        //     ];
        // }
        foreach ($childs as $child) {
            $enfants[$child->getId()] = [
                'firstname' => $child->getFirstname(),
            ];
        }
        $childs = json_encode($enfants);

        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : Vérifier que le rendez-vous n'est pas déjà pris (si code javascript modifié par un hacker) ou pris entre temps par un autre utilisateur
            $appointmentsRepository->save($appointment, true);
            // TODO : Ajouter un message flash indiquant que le rendez-vous a bien été créé
            $this->addFlash('success', 'Votre rendez-vous à étais pris en compte');
            // TODO : Envoyer un mail de confirmation au client
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
            'cares' => $caresRepository->findAll(),
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

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Appointments $appointment, AppointmentsRepository $appointmentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $appointmentsRepository->remove($appointment, true);
        }

        return $this->redirectToRoute('profile/profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
