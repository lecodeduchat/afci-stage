<?php

namespace App\Classes;

use App\Repository\DaysOnRepository;
use App\Repository\AppointmentsRepository;
use App\Repository\DaysOffRepository;

class Slots
{
    private $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    const MONTHS = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Novembre", "Décembre"];
    private $appointmentsRepository;
    private $daysOnRepository;
    private $daysOffRepository;

    public function __construct(AppointmentsRepository $appointmentsRepository, DaysOnRepository $daysOnRepository, DaysOffRepository $daysOffRepository)
    {
        $this->appointmentsRepository = $appointmentsRepository;
        $this->daysOnRepository = $daysOnRepository;
        $this->daysOffRepository = $daysOffRepository;
    }

    public function getSlots(): array
    {
        // Je récupère la date du jour
        $date = new \DateTime();
        $timeNow = $date;
        // Je la formate pour la passer en paramètre à la requête
        $dateNow = $date->format('Y-m-d');
        // Je récupère tous les jours ouvrés depuis la date du jour
        $daysOn = $this->daysOnRepository->findAllSince($dateNow);
        // Je récupère tous les rendez-vous depuis la date du jour
        $appointments = $this->appointmentsRepository->findAllSince($dateNow);
        // Je récupère les indisponibilités depuis la date du jour
        $daysOff = $this->daysOffRepository->findAllSince($dateNow);

        // Je crée un tableau pour stocker les créneaux horaires de chaque jour
        $slots = [];
        // Je crée un compteur pour parcourir le tableau des créneaux horaires
        $i = 0;
        $slot = 0;

        // Je parcours les créneaux horaires de chaque jour pour récupérer l'amplitude horaire de chaque jour
        foreach ($daysOn as $dayOn) {
            // Je crée une date pour chaque journée de rendez-vous
            $slots[$i]["date"] = $dayOn->getDate()->format('Y-m-d');
            // Je récupère le numéro du jour de la semaine
            $numDay = date('N', strtotime($slots[$i]["date"]));
            // Je récupère le nom du jour de la semaine en retirant 1 au numéro du jour car le tableau commence à l'indice 0
            $nameday = $this->days[$numDay - 1];
            // Je stocke le nom du jour dans le tableau
            $slots[$i]["day"] = $nameday;
            // Je crée un tableau pour stocker les créneaux horaires de chaque jour
            $slots[$i]["slots"] = [];

            // Je crée un tableau avec les rendez-vous du jour et les indisponibilités du jour
            $hoursOff = [];

            foreach ($daysOff as $dayOff) {
                if ($dayOff->getStart()->format('Y-m-d') == $slots[$i]["date"]) {
                    $start = strtotime($dayOff->getStart()->format("H:i"));
                    $hoursOff[$start] = [
                        "start" => $dayOff->getStart()->format('H:i'),
                        "end" => $dayOff->getEnd()->format('H:i'),
                    ];
                }
            }

            foreach ($appointments as $appointment) {
                if ($appointment->getDate()->format('Y-m-d') == $slots[$i]["date"]) {
                    $start = strtotime($appointment->getDate()->format("H:i"));
                    $duration = $appointment->getCare()->getDuration()->format("i");
                    $end = $start + $duration * 60;
                    $end = date("H:i", $end);
                    $hoursOff[$start] = [
                        "start" => $appointment->getDate()->format('H:i'),
                        "end" => $end,
                    ];
                }
            }
            // Je trie le tableau par ordre croissant
            ksort($hoursOff);

            // Je vérifie si la matinée est ouverte
            if ($dayOn->getStartMorning() != null || $dayOn->getStartMorning()->format("H:i") != "00:00") {
                $startMorning = $dayOn->getStartMorning()->format("H:i");
                $endMorning = $dayOn->getEndMorning()->format("H:i");
                // Je fixe la limite de créneaux horaires pour la matinéeen me basant sur des créneaux de 30 minutes
                $limitMorning = strtotime($endMorning) - 30 * 60;
                $is_open_morning = true;
            }
            // Je vérifie si l'après-midi est ouvert
            if ($dayOn->getStartAfternoon() != null || $dayOn->getStartAfternoon()->format("H:i") != "00:00") {
                $startAfternoon = $dayOn->getStartAfternoon()->format("H:i");
                $endAfternoon = $dayOn->getEndAfternoon()->format("H:i");
                // Je fixe la limite de créneaux horaires pour l'après-midi en me basant sur des créneaux de 30 minutes
                $limitAfternoon = strtotime($endAfternoon) - 30 * 60;
                $is_open_afternoon = true;
            }

            // Je crée la variable $slot, je lui attribut la valeur du début de matinée sauf si le jour est aujourd'hui et que l'heure actuelle est supérieure à l'heure de début de matinée
            if ($dateNow == $slots[$i]["date"]) {
                if ($is_open_morning == true) {
                    // dd($timeNow, $startMorning, $limitMorning);
                    if ($timeNow->format("H:i") < $startMorning) {
                        $slot = strtotime($startMorning);
                    } elseif ($timeNow->format("H:i") < date("H:i", $limitMorning)) {
                        $hour = $timeNow->format("H");
                        $minutes = $timeNow->format("i");
                        if ($minutes < 30) {
                            $slot = mktime($hour, 30, 0, date('m'), date('d'), date('Y'));;
                        } else {
                            $hour = $hour + 1;
                            $slot = mktime($hour, 0, 0, date('m'), date('d'), date('Y'));;
                        }
                    }
                }
                if ($is_open_afternoon == true && $slot > $limitMorning) {
                    if ($timeNow->format("H:i") < $startAfternoon) {
                        $slot = strtotime($startAfternoon);
                    } elseif ($timeNow < $limitAfternoon) {
                        $hour = $timeNow->format("H");
                        $minutes = $timeNow->format("i");
                        if ($minutes < 30) {
                            $slot = mktime($hour, 30, 0, date('m'), date('d'), date('Y'));;
                        } else {
                            $hour = $hour + 1;
                            $slot = mktime($hour, 0, 0, date('m'), date('d'), date('Y'));;
                        }
                    }
                }
            } else {
                if ($is_open_morning == true) {
                    $slot = strtotime($startMorning);
                } else {
                    $slot = strtotime($startAfternoon);
                }
            }
            $cpt = 0;
            foreach ($hoursOff as $hourOff) {
                while ($slot < strtotime($hourOff["start"])) {
                    if ($slot < $limitMorning) {
                        $horaire = date("H:i", $slot);
                        array_push($slots[$i]["slots"], $horaire);
                        // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                        $slot = $slot + 30 * 60;
                    } elseif ($slot >= $limitMorning && $slot < strtotime($startAfternoon) && $is_open_afternoon == true) {
                        if ($slot == $limitMorning) {
                            $horaire = date("H:i", $slot);
                            array_push($slots[$i]["slots"], $horaire);
                        }
                        // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                        $slot = strtotime($startAfternoon);
                    } elseif ($slot >= strtotime($startAfternoon) && $slot < $limitAfternoon && $is_open_afternoon == true) {
                        $horaire = date("H:i", $slot);
                        array_push($slots[$i]["slots"], $horaire);
                        // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                        $slot = $slot + 30 * 60;
                    }
                    $cpt++;
                    if ($cpt > 100) {
                        break;
                    }
                }
            }

            // Si aucun rendez-vous et aucune indisponibilité ne correspond à la date du jour boucle selon le moment de la journée
            if ($is_open_morning == true && count($hoursOff) == 0 && $timeNow->format("H:i") < $slot) {
                $cpt = 0;
                while (
                    $slot <= $limitMorning
                ) {
                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = $slot + 30 * 60;
                    $cpt++;
                    if ($cpt > 100) {
                        break;
                    }
                }
            }
            if ($is_open_afternoon == true && count($hoursOff) == 0) {
                $slot = strtotime($startAfternoon);
                $cpt = 0;
                while (
                    $slot <= $limitAfternoon
                ) {

                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = $slot + 30 * 60;
                    $cpt++;
                    if ($cpt > 100) {
                        break;
                    }
                }
            }
            $i++;
        }
        return $slots;
    }
}
