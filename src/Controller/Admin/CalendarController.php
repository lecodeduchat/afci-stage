<?php

namespace App\Controller\Admin;

use App\Entity\Appointments;
use App\Repository\AppointmentsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CalendarController extends AbstractController
{
    // Fonction qui met à jour un évènement suite à un drag and drop
    // La méthode PUT est utilisée pour mettre à jour un event ou le créer si il n'existe pas
    #[Route('/calendrier/edition/{id}', name: 'calendar_edit', methods: ['PUT'])]
    public function update(Appointments $appointments, Request $request, EntityManagerInterface $em): Response
    {
        // On récupère les données envoyées par le front
        $data = json_decode($request->getContent());
        // On vérifie si on a bien toutes les données nécessaires pour créer modifier un rendez-vous
        if (
            isset($data->title) && !empty($data->title) &&
            isset($data->start) && !empty($data->start) &&
            isset($data->color) && !empty($data->color)
        ) {
            // Les données sont complètes, on peut modifier un rendez-vous
            // On initialise un code de retour: 200 qui veut dire ok la mise à jour est faite
            $code = 200;
            // On vérifie si le rendez-vous existe
            if (!$appointments) {
                // Le rendez-vous n'existe pas, on le crée
                $appointments = new Appointments();
                // On change le code de retour: 201 qui veut dire que le rendez-vous est créé
                $code = 201;
            }
            if ($appointments) {
                // On hydrate l'objet rendez-vous avec les données envoyées par le front
                $date = new DateTime($data->start);
                $appointments->setDate($date);
                //On envoye les données dans la base de données
                $em->persist($appointments);
                $em->flush();
            }
            return new Response('ok', $code);
        } else {
            // On retourne une erreur, les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    public function edit()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }
}
