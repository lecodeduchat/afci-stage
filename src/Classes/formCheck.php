<?php

namespace App\Classes;

class formCheck
{
    // Constantes de validation des données
    // Regex communes pour les champs de formulaire de type texte (nom, prénom, ville, pays, etc.)
    const REGEX_TEXT = "/^[a-z]+[ \-']?[[a-z]+[ \-']?]*[a-z]+$/";
    const REGEX_PHONE = "/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/";
    const REGEX_MAIL = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$/";
    const REGEX_DATE = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
    const REGEX_HOUR = "/^[0-9]{2}:[0-9]{2}$/";

    // Méthode pour vérifier les données saisies dans les champs de formulaire
    public function checkPhone(string $phone): bool
    {
        $phone = $this->cleanData($phone);
        // Je teste si le numéro de téléphone correspond à la regex
        if (!preg_match(self::REGEX_PHONE, $phone)) {
            // Si le numéro de téléphone ne correspond pas à la regex, je retourne false
            return false;
        }
        return true;
    }

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
