<?php

namespace App\Classes;

use App\Repository\DaysOnRepository;
use App\Repository\AppointmentsRepository;


class Slots
{
    private $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    const MONTHS = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Novembre", "Décembre"];
    private $appointmentsRepository;
    private $daysOnRepository;

    public function __construct(AppointmentsRepository $appointmentsRepository, DaysOnRepository $daysOnRepository)
    {
        $this->appointmentsRepository = $appointmentsRepository;
        $this->daysOnRepository = $daysOnRepository;
    }

    public function getSlots(): array
    {
        // Je récupère la date du jour
        $date = new \DateTime();
        $timeNow = $date;
        // Je la formate pour la passer en paramètre à la requête
        $date = $date->format('Y-m-d');
        // Je récupère tous les jours ouvrés depuis la date du jour
        $daysOn = $this->daysOnRepository->findAllSince($date);
        // Je récupère tous les rendez-vous depuis la date du jour
        $appointments = $this->appointmentsRepository->findAllSince($date);
        // Je crée un tableau pour stocker les créneaux horaires de chaque jour
        $slots = [];
        // Je crée un compteur pour parcourir le tableau des créneaux horaires
        $i = 0;

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
            if ($dayOn->getStartMorning() != null) {
                $startMorning = $dayOn->getStartMorning()->format("H:i");
                $endMorning = $dayOn->getEndMorning()->format("H:i");
            }
            // Je vérifie si l'après-midi est ouvert
            if ($dayOn->getStartAfternoon() != null) {
                $startAfternoon = $dayOn->getStartAfternoon()->format("H:i");
                $endAfternoon = $dayOn->getEndAfternoon()->format("H:i");
            }
            // Je fixe la limite de créneaux horaires pour la matinée et l'après-midi en me basant sur des créneaux de 30 minutes
            $limitMorning = strtotime($endMorning) - 30 * 60;
            $limitAfternoon = strtotime($endAfternoon) - 30 * 60;
            // Je crée la variable $slot, je lui attribut la valeur du début de matinée sauf si le jour est aujourd'hui et que l'heure actuelle est supérieure à l'heure de début de matinée
            if ($i == 0 && $timeNow->format("H:I") > $dayOn->getStartMorning()->format("H:i")) {
                $hour = $timeNow->format("H");
                $minutes = $timeNow->format("i");
                if ($minutes < 30) {
                    $slot = mktime($hour, 30, 0, date('m'), date('d'), date('Y'));;
                } else {
                    $hour = $hour + 1;
                    $slot = mktime($hour, 0, 0, date('m'), date('d'), date('Y'));;
                }
            } else {
                $slot = strtotime($startMorning);
            }

            foreach ($appointments as $appointment) {
                if ($appointment->getDate()->format('Y-m-d') == $slots[$i]["date"]) {
                    // Si un rendez-vous correspond à la date disponible
                    // Je récupère l'heure du rendez-vous
                    $time = strtotime($appointment->getDate()->format("H:i"));
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
                        } elseif ($slot >= $limitMorning && $slot < strtotime($startAfternoon)) {
                            if ($slot == $limitMorning) {
                                $horaire = date("H:i", $slot);
                                array_push($slots[$i]["slots"], $horaire);
                            }
                            // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                            $slot = strtotime($startAfternoon);
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
            while (
                $slot <= $limitAfternoon
            ) {
                if (
                    $slot < $limitMorning
                ) {
                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = $slot + 30 * 60;
                } elseif ($slot >= $limitMorning && $slot < strtotime($startAfternoon)) {
                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = strtotime($startAfternoon);
                } else {
                    $horaire = date("H:i", $slot);
                    array_push($slots[$i]["slots"], $horaire);
                    // le timestamp étant en millisecondes, pour ajouter 30 minutes, je multiplie par 60
                    $slot = $slot + 30 * 60;
                }
            }
            $i++;
        }
        return $slots;
    }
}
