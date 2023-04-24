<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class TwigDateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('dayName', [$this, 'formatDayName']),
            new TwigFilter('monthName', [$this, 'formatMonthName']),
        ];
    }

    public function formatDayName($date): string
    {
        // Je récupère le jour de la semaine au format numérique
        $numDay = $date->format('N');
        // Je crée un tableau contenant les jours de la semaine
        $days = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
        ];
        // Je récupère le jour de la semaine en toutes lettres
        $dayName = $days[$numDay];
        return $dayName;
    }
    public function formatMonthName($date): string
    {
        // Je récupère le mois au format numérique
        $numMonth = $date->format('m');
        // Je convertis le mois au format numérique en entier pour éviter les erreurs dû au zéro devant le chiffre
        $numMonth = (int) $numMonth;
        // Je crée un tableau contenant les mois de l'année
        $months = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months[$numMonth];
        return $monthName;
    }
}
