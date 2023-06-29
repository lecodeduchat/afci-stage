<?php

namespace App\Classes;

class formCheck
{
    public function cleanData(string $data): string
    {
        // Retire les espaces en début et fin de chaîne
        $data = trim($data);
        // Ajoute des antislashs pour empêcher l'exécution de code SQL
        $data = addslashes($data);
        // Convertit les caractères spéciaux en entités HTML
        $data = htmlspecialchars($data);
        // Retire les balises HTML et PHP
        $data = strip_tags($data);

        return $data;
    }
}
