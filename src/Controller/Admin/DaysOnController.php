<?php

namespace App\Controller\Admin;

use App\Entity\DaysOn;
use App\Form\DaysOnType;
use App\Repository\DaysOnRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/Admin/jours-ouvres', name: 'daysOn')]
class DaysOnController extends AbstractController
{
    #[Route('/', name: '_index', methods: ['GET'])]
    public function index(DaysOnRepository $daysOnRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $daysOnRepository->paginationQuery(),
            // je recupère la page et par defaut je lui met la 1
            $request->query->get('page', 1),
            15
        );
        return $this->render('admin/days_on/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/nouveau', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DaysOnRepository $daysOnRepository): Response
    {
        $daysOn = new DaysOn();
        $daysOnForm = $this->createForm(DaysOnType::class, $daysOn);
        $daysOnForm->handleRequest($request);

        if ($daysOnForm->isSubmitted() && $daysOnForm->isValid()) {
            $date = $daysOnForm->get('date')->getData();

            // Je vérifie que la date n'est pas déjà un jour ouvré
            $daysOnExist = $daysOnRepository->findOneBy(['date' => $date]);
            if ($daysOnExist) {
                $this->addFlash('danger', 'Il y a déjà un jour ouvré à cette date.');
                return $this->redirectToRoute('daysOn_new');
            }
            // Je vérifie que les heures de début et de fin de matinée sont cohérentes
            $startMorning = $daysOnForm->get('start_morning')->getData();
            $endMorning = $daysOnForm->get('end_morning')->getData();
            if ($startMorning > $endMorning) {
                $this->addFlash('danger', 'L\'heure de début de matinée doit être inférieure à l\'heure de fin de matinée');
                return $this->redirectToRoute('daysOn_new');
            }
            $startAfternoon = $daysOnForm->get('start_afternoon')->getData();
            $endAfternoon = $daysOnForm->get('end_afternoon')->getData();
            if ($startAfternoon > $endAfternoon) {
                $this->addFlash('danger', 'L\'heure de début d\'après-midi doit être inférieure à l\'heure de fin d\'après-midi');
                return $this->redirectToRoute('daysOn_new');
            }

            $daysOnRepository->save($daysOn, true);

            return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_on/new.html.twig', [
            'days_on' => $daysOn,
            'daysOnForm' => $daysOnForm,
        ]);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(DaysOn $daysOn): Response
    {
        return $this->render('days_on/show.html.twig', [
            'days_on' => $daysOn,
        ]);
    }

    #[Route('/{id}/modifier', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DaysOn $daysOn, DaysOnRepository $daysOnRepository): Response
    {
        $daysOnForm = $this->createForm(DaysOnType::class, $daysOn);
        $daysOnForm->handleRequest($request);

        if ($daysOnForm->isSubmitted() && $daysOnForm->isValid()) {
            $daysOnRepository->save($daysOn, true);

            return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/days_on/edit.html.twig', [
            'days_on' => $daysOn,
            'daysOnForm' => $daysOnForm,
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, DaysOn $daysOn, DaysOnRepository $daysOnRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $daysOn->getId(), $request->request->get('_token'))) {
            $daysOnRepository->remove($daysOn, true);
        }

        return $this->redirectToRoute('daysOn_index', [], Response::HTTP_SEE_OTHER);
    }
}
