<?php

namespace App\Classes;

use App\Repository\CaresRepository;

class Soins
{
    private $caresRepository;

    public function  __construct(CaresRepository $caresRepository)
    {
        $this->caresRepository = $caresRepository;
    }

    public function getSoins(): string
    {
        $cares = $this->caresRepository->findAll();
        $soins = [];
        $i = 0;
        foreach ($cares as $care) {
            $soins[$i] = [
                'id' => $care->getId(),
                'name' => $care->getName(),
                'duration' => $care->getDuration()->format('H:i'),
                'price' => $care->getPrice(),
            ];
            $i++;
        }
        $soins = json_encode($soins);
        return $soins;
    }
}
