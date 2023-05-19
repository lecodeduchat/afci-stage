<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class TwigPhoneExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('phone', [$this, 'formatPhone']),
        ];
    }

    public function formatPhone($phone): string
    {
        if ($phone) {
            $phone = "0" . $phone;
            // Ajout d'un point tous les 2 caractères
            $phone = chunk_split($phone, 2, '.');
        } else {
            $phone = "Non renseigné";
        }

        return $phone;
    }
}
