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
    private $careDuration;

    public function __construct(AppointmentsRepository $appointmentsRepository, DaysOnRepository $daysOnRepository, DaysOffRepository $daysOffRepository, $careDuration)
    {
        $this->appointmentsRepository = $appointmentsRepository;
        $this->daysOnRepository = $daysOnRepository;
        $this->daysOffRepository = $daysOffRepository;
        $this->careDuration = $careDuration;
    }

    public function getSlots(): array
    {
        // Je récupère la date du jour
        $date = new \DateTime();
        // J'ajoute 1 jour à la date du jour pour les tests
        // $date->add(new \DateInterval('P1D'));

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

            // Je vérifie si la matinée est ouverte
            if ($dayOn->getStartMorning()->format("H:i") != "00:00") {
                $startDay = $dayOn->getStartMorning()->format("H:i");
                $endMorning = $dayOn->getEndMorning()->format("H:i");
                // Je fixe la limite de créneaux horaires pour la matinée en me basant sur des créneaux de 30 minutes
                $limitMorning = strtotime($endMorning) - 30 * 60;
                $is_open_morning = true;
            } else {
                $is_open_morning = false;
                $startDay = $dayOn->getStartAfternoon()->format("H:i");
            }
            // Je vérifie si l'après-midi est ouvert
            if ($dayOn->getStartAfternoon()->format("H:i") != "00:00") {
                $endDay = $dayOn->getEndAfternoon()->format("H:i");
                $startAfternoon = $dayOn->getStartAfternoon()->format("H:i");
                $endAfternoon = $dayOn->getEndAfternoon()->format("H:i");
                // Je fixe la limite de créneaux horaires pour l'après-midi en me basant sur des créneaux de 30 minutes
                $limitAfternoon = strtotime($endAfternoon) - 30 * 60;
                $is_open_afternoon = true;
            } else {
                $is_open_afternoon = false;
                $endDay = $dayOn->getEndMorning()->format("H:i");
            }

            // Je crée un tableau avec les rendez-vous du jour, les indisponibilités du jour et le cas échéant la pause déjeuner
            $hoursOff = [];

            // Si la matinée et l'après-midi sont ouvertes, j'ajoute la pause déjeuner dans le tableau des indisponibilités
            if ($is_open_morning == true && $is_open_afternoon == true) {
                $hoursOff[strtotime($endMorning)] = [
                    "start" => $endMorning,
                    "end" => $startAfternoon,
                ];
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
            foreach ($daysOff as $dayOff) {
                if ($dayOff->getStart()->format('Y-m-d') == $slots[$i]["date"]) {
                    $start = strtotime($dayOff->getStart()->format("H:i"));
                    $hoursOff[$start] = [
                        "start" => $dayOff->getStart()->format('H:i'),
                        "end" => $dayOff->getEnd()->format('H:i'),
                    ];
                }
            }

            // Je trie le tableau par ordre croissant
            ksort($hoursOff);
            // dd($hoursOff);
            // Je mets à jour la fin de journée en fonction de la dernière indisponibilité
            $end = end($hoursOff);
            // echo "end: ", $end["end"], " - endDay: ", $endDay, "<br>";
            if ($endDay < $end["end"]) {
                $endDay = $end["end"];
            }

            // Je crée la variable $slot, je lui attribut la valeur du début de journée sauf si le jour est aujourd'hui et que l'heure actuelle est supérieure à l'heure de début de journée
            if ($slots[$i]["date"] == $dateNow && $timeNow->format("H:i") > $startDay) {
                $hour = $timeNow->format("H");
                $minutes = $timeNow->format("i");
                if ($minutes < 30) {
                    $slot = mktime($hour, 30, 0, date('m'), date('d'), date('Y'));;
                } else {
                    $hour = $hour + 1;
                    $slot = mktime($hour, 0, 0, date('m'), date('d'), date('Y'));;
                }
            } else {
                $slot = strtotime($startDay);
            }
            // echo "Date: ", $slots[$i]["date"], " - slot de départ: ", date("H:i", $slot), "<br>";
            // echo "-------------------------------------------------------------------------------- <br>";
            $careDuration = $this->careDuration;
            // Je parcours le tableau des indisponibilités pour récupérer les créneaux horaires disponibles
            $cpt = 0;
            $j = 0;
            foreach ($hoursOff as $hourOff) {
                // echo $j, " slot de départ bis: ", date("H:i", $slot), " -start :", $hourOff["start"], " - end: ", date("H:i", strtotime($hourOff["end"])),  "<br>";
                while ($slot < strtotime($hourOff["start"])) {
                    $diff = strtotime($hourOff["start"]) - $slot;
                    // echo "diff : " . $diff . "<br>";
                    // echo "slot: ", date("H:i", $slot), " - start: ", date("H:i", strtotime($hourOff["start"])), " - end: ", date("H:i", strtotime($hourOff["end"])), "- diff: ", $diff / 60, '<br>';
                    if ($diff >= $careDuration) {
                        $horaire = date("H:i", $slot);
                        array_push($slots[$i]["slots"], $horaire);
                        // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                        $slot = $slot + 30 * 60;
                    } else {
                        $slot = strtotime($hourOff["end"]);
                    }
                    $cpt++;
                    if ($cpt > 100) {
                        break;
                    }
                }
                if ($slot <= strtotime($hourOff["end"])) {
                    $slot = strtotime($hourOff["end"]);
                }

                $j++;
            }
            // Si aucun rendez-vous et aucune indisponibilité ne correspond à la date du jour
            // OU si l'heure actuelle est inférieure à l'heure de fin de journée
            $cpt = $diff = 0;
            // dd($slot, $endDay);
            // echo "-------------------------------------------------------------------------------- <br>";
            // echo "slot de départ ter: ", date("H:i", $slot), "<br>";
            while (
                $slot < strtotime($endDay)
            ) {
                $diff = strtotime($endDay) - $slot;
                if ($diff >= $careDuration) {
                    // echo "slot : ", date("H:i", $slot), " - diff: ", $diff, '<br>';
                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = $slot + 30 * 60;
                } else {
                    $slot = strtotime($hourOff["end"]);
                }
                $cpt++;
                if ($cpt > 20) {
                    break;
                }
            }
            // echo "-------------------------------------------------------------------------------- <br>";
            // echo "Fin de journée: ", $endDay, " - slot: ", date("H:i", $slot), "<br>";
            // echo "-------------------------------------------------------------------------------- <br>";
            $i++;
        }
        return $slots;
    }
}
