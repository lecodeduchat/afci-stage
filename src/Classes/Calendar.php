<?php

namespace App\Classes;

use DateTime;
use App\Repository\UsersRepository;
use App\Repository\DaysOnRepository;
use App\Repository\DaysOffRepository;
use App\Repository\AppointmentsRepository;

class Calendar
{
    private $appointmentsRepository;
    private $daysOnRepository;
    private $daysOffRepository;
    private $usersRepository;

    public function __construct(AppointmentsRepository $appointmentsRepository, DaysOnRepository $daysOnRepository, DaysOffRepository $daysOffRepository, UsersRepository $usersRepository)
    {
        $this->appointmentsRepository = $appointmentsRepository;
        $this->daysOnRepository = $daysOnRepository;
        $this->daysOffRepository = $daysOffRepository;
        $this->usersRepository = $usersRepository;
    }

    public function getAppointments(): array
    {
        // J'initialise un tableau vide qui va contenir les données formatées
        $rdvs = [];
        // Je récupère tous les rendez-vous
        $appointments = $this->appointmentsRepository->findAll();
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
            $user = $this->usersRepository->find($userId);
            $user = $user->getFirstname() . ' ' . $user->getLastname();
            // Je récupère l'intitulé du soin
            $care = $appointment->getCare()->getName();
            $title = $user . ' - ' . $care;
            // Je récupère la couleur du soin
            $color = $appointment->getCare()->getColor();
            // J'insère les données dans le tableau des rendez-vous
            $rdvs[$start] = [
                'id' => $appointment->getId(),
                'start' => $start,
                'end' => $end,
                'title' => $title,
                'color' => $color,
                'textColor' => '#000',
            ];
        }

        return $rdvs;
    }

    public function getHoursOff(): array
    {
        // J'initialise un tableau vide qui va contenir les jours et heures non travaillés
        $hoursOff = [];
        // Je récupère la date du jour
        $date = new DateTime();
        // J'enlève un mois à la date du jour
        $date->modify('-1 month');
        // Je récupère tous les jours travaillés
        $daysOn = $this->daysOnRepository->findAllSince($date);
        $daysOff = $this->daysOffRepository->findAllSince($date);

        // Je range les jours de repos et indisponibilités dans le tableau $hoursOff
        foreach ($daysOff as $dayOff) {
            if ($dayOff->getStart()->format("Y-m-d") == "2023-05-25") {
                // echo $dayOff->getStart()->format("Y-m-d H:i:s") . " - " . $dayOff->getEnd()->format("Y-m-d H:i:s") . '<br>';
            }
            $key = $dayOff->getStart()->format("Y-m-d H:i:s");
            $hoursOff[$key] = [
                'id' => $dayOff->getId(),
                'start' => $dayOff->getStart()->format("Y-m-d H:i:s"),
                'end' => $dayOff->getEnd()->format("Y-m-d H:i:s"),
                'title' => $dayOff->getTitle(),
                'color' => $dayOff->getColor(),
                'textColor' => '#000',
            ];
            if ($dayOff->getStart()->format("Y-m-d") == "2023-05-25") {
                // dd($hoursOff[$key]);
            }
        }

        // Je range les heures de repos des jours ouvrés dans le tableau $hoursOff
        foreach ($daysOn as $dayOn) {
            $startMorning = $dayOn->getStartMorning();
            $endMorning = $dayOn->getEndMorning();
            $startAfternoon = $dayOn->getStartAfternoon();
            $endAfternoon = $dayOn->getEndAfternoon();
            $day = $dayOn->getDate()->format('Y-m-d');
            $endDay = mktime(20, 0, 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
            $endDay = new DateTime(date("Y-m-d H:i:s", $endDay));
            $endDayTs = strtotime($endDay->format("Y-m-d H:i:s"));
            // dd($endDay);
            // Je vérifie si la matinée est ouverte
            $isMorningOpen = $startMorning->format('H:i:s') != "00:00:00" ? true : false;
            // Si la matinée est ouverte, je crée des objets DateTime pour pouvoir les comparer
            if ($isMorningOpen) {
                $startMorning = mktime($startMorning->format('H'), $startMorning->format('i'), 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
                $startMorning = new DateTime(date("Y-m-d H:i:s", $startMorning));
                $endMorning = mktime($endMorning->format('H'), $endMorning->format('i'), 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
                $endMorning = new DateTime(date("Y-m-d H:i:s", $endMorning));
            }
            // Je vérifie si l'après-midi est ouverte
            $isAfternoonOpen = $startAfternoon->format('H:i:s') != "00:00:00" ? true : false;
            // Si l'après-midi est ouverte, je crée des objets DateTime pour pouvoir les comparer
            if ($isAfternoonOpen) {
                $startAfternoon = mktime($startAfternoon->format('H'), $startAfternoon->format('i'), 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
                $startAfternoon = new DateTime(date("Y-m-d H:i:s", $startAfternoon));
                $endAfternoon = mktime($endAfternoon->format('H'), $endAfternoon->format('i'), 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
                $endAfternoon = new DateTime(date("Y-m-d H:i:s", $endAfternoon));
            }
            // Je vérifie si le jour est ouvert toute la journée
            $isAllDayOpen = $isMorningOpen && $isAfternoonOpen ? true : false;
            $isDayOff = false;
            // Je crée une variable qui va me permettre de parcourir les heures de la journée
            $step = mktime(8, 30, 0, $dayOn->getDate()->format('m'), $dayOn->getDate()->format('d'), $dayOn->getDate()->format('Y'));
            $step = new DateTime(date("Y-m-d H:i:s", $step));
            // echo $step->format("Y-m-d H:i:s") . '<br>';
            foreach ($daysOff as $dayOff) {
                if ($day == $dayOff->getStart()->format("Y-m-d")) {
                    $isDayOff = true;
                    // Je colore les heures non travaillées du début de matinée
                    if (
                        $isMorningOpen
                        && $step->format("H:i:s") < $dayOff->getStart()->format("H:i:s")
                        && $dayOff->getStart()->format("H:i:s") < $startMorning->format("H:i:s")
                    ) {
                        $key = $day . ' 08:30:00';
                        $hoursOff[$key] = [
                            'id' => 0,
                            'start' => $day . ' 08:30:00',
                            'end' => $dayOff->getStart()->format("Y-m-d H:i:s"),
                            'title' => "Heures non travaillées",
                            'color' => '#FFCAB1',
                            'textColor' => '#000',
                        ];
                        $step = $dayOff->getEnd();
                    }
                    // Je colore les heures non travaillées de la fin de matinée
                    if ($day == "2023-05-25") {
                        // echo $step->format("Y-m-d H:i:s") . " - " . $dayOff->getEnd()->format("H:i:s") . '<br>';
                    }
                    if (
                        $isAllDayOpen
                        && $step->format("H:i:s") < $endMorning->format("H:i:s")
                        && $endMorning->format("H:i:s") < $dayOff->getEnd()->format("H:i:s")
                        && $dayOff->getEnd()->format("H:i:s") < $startAfternoon->format("H:i:s")
                    ) {
                        $key = $dayOff->getEnd()->format("Y-m-d H:i:s");
                        $hoursOff[$key] = [
                            'id' => 0,
                            'start' => $dayOff->getEnd()->format("Y-m-d H:i:s"),
                            'end' => $startAfternoon->format("Y-m-d H:i:s"),
                            'title' => "Pause déjeuner",
                            'color' => '#FFCAB1',
                            'textColor' => '#000',
                        ];
                        $step = $startAfternoon;
                    } elseif (
                        $isAllDayOpen
                        && $step->format("H:i:s") < $endMorning->format("H:i:s")
                        && $dayOff->getStart()->format("H:i:s") > $endMorning->format("H:i:s")
                        && $dayOff->getStart()->format("H:i:s") < $startAfternoon->format("H:i:s")
                        && $dayOff->getEnd()->format("H:i:s") > $startAfternoon->format("H:i:s")
                    ) {
                        $key = $endMorning->format("Y-m-d H:i:s");
                        $hoursOff[$key] = [
                            'id' => 0,
                            'start' => $endMorning->format("Y-m-d H:i:s"),
                            'end' => $dayOff->getStart()->format("Y-m-d H:i:s"),
                            'title' => "Pause déjeuner",
                            'color' => '#FFCAB1',
                            'textColor' => '#000',
                        ];
                        $step = $startAfternoon;
                    } else if (
                        $isAllDayOpen
                        && $step->format("H:i:s") < $endMorning->format("H:i:s")
                        && $dayOff->getStart()->format("H:i:s") < $endMorning->format("H:i:s")
                        && $dayOff->getEnd()->format("H:i:s") > $startAfternoon->format("H:i:s")
                    ) {
                        $step = $startAfternoon;
                    }
                }
            }
            // Si il y a eu des indiponibilités mais qu'il reste des heures à colorer
            $cpt = 0;

            while ($step < $endDay) {
                if (
                    $isMorningOpen
                    && $step < $startMorning
                ) {
                    $key = $day . ' 08:30:00';
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $step->format("Y-m-d H:i:s"),
                        'end' => $startMorning->format("Y-m-d H:i:s"),
                        'title' => "Heures non travaillées",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                    $step = $startMorning;
                }
                if (
                    !$isMorningOpen
                    && $step < $startAfternoon
                ) {
                    $key = $day . ' 13:00:00';
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' 13:00:00',
                        'end' => $startAfternoon->format("Y-m-d H:i:s"),
                        'title' => "Pause déjeuner",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                    $step = $startAfternoon;
                }
                if (
                    $isAllDayOpen
                    && $step < $endMorning
                ) {
                    $key = $day . ' ' . $endMorning->format("H:i:s");
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $endMorning->format("Y-m-d H:i:s"),
                        'end' => $startAfternoon->format("Y-m-d H:i:s"),
                        'title' => "Pause repas",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                    $step = $startAfternoon;
                }
                if (
                    $isAfternoonOpen
                    && $step < $endAfternoon
                ) {
                    $key = $day . ' ' . $endAfternoon->format("H:i:s");
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $endAfternoon->format("Y-m-d H:i:s"),
                        'end' => $endDay->format("Y-m-d H:i:s"),
                        'title' => "Heures non travaillées",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                    $step = $endDay;
                }

                // Sécurité pour éviter une boucle infinie
                $cpt++;
                if ($cpt > 10) {
                    break;
                }
            }

            // Si il n'y a pas d'indisponibilité, je colore les heures non travaillées
            if ($isDayOff == false) {
                if ($isMorningOpen) {
                    $key = $day . ' 08:30:00';
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' 08:30:00',
                        'end' => $day . ' ' . $startMorning->format("H:i:s"),
                        'title' => "Heures non travaillées",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                } else {
                    $key = $day . ' 08:30:00';
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' 08:30:00',
                        'end' => $day . ' 13:00:00',
                        'title' => "Matinée non travaillée",
                        'color' => '#ddd',
                        'textColor' => '#62B6CB',
                    ];
                }
                if ($isAllDayOpen) {
                    $key = $day . ' ' . $endMorning->format("H:i:s");
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' ' . $endMorning->format("H:i:s"),
                        'end' => $day . ' ' . $startAfternoon->format("H:i:s"),
                        'title' => "Pause repas",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                }
                if ($isAfternoonOpen) {
                    $key = $day . ' ' . $endAfternoon->format("H:i:s");
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' ' . $endAfternoon->format("H:i:s"),
                        'end' => $day . ' 20:00:00',
                        'title' => "Heures non travaillées",
                        'color' => '#FFCAB1',
                        'textColor' => '#000',
                    ];
                } else {
                    $key = $day . ' 13:00:00';
                    $hoursOff[$key] = [
                        'id' => 0,
                        'start' => $day . ' 13:00:00',
                        'end' => $day . ' 20:00:00',
                        'title' => "Après-midi non travaillée",
                        'color' => '#ddd',
                        'textColor' => '#62B6CB',
                    ];
                }
            }
        }
        // dd($hoursOff);
        ksort($hoursOff);
        // dd($hoursOff);
        return $hoursOff;
    }

    // public function createTimestamp($day, $time): string
    // {
    //     $date = mktime();
    //     $timestamp = strtotime($date->format('Y-m-d H:i:s'));
    //     return $timestamp;
    // }
}
