<?php

namespace App\Controller;

use App\Entity\DemandesProspection;
use App\Service\NotificationService;
use App\Form\DemandesProspectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/prospection")
 */
class DemandeProspectionController extends AbstractController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @Route("/", name="app_demande_prospection_index", methods={"GET"})
     */
    public function index(DemandesProspectionRepository $demandesProspectionRepository): Response
    {
        return $this->render('demande_prospection/index.html.twig', [
            'demandes_prospections' => $demandesProspectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_demande_prospection_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prospection = new DemandesProspection();
        $form = $this->createForm(DemandesProspectionType::class, $prospection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prospection->setStatut('attente');
            $prospection->setDateDemande(new \DateTime());

            // Assigner l'agent à la demande de prospection
            if ($this->isGranted('ROLE_AGENT')) {
                $prospection->setAgent($this->getUser());
            } else {
                $this->addFlash('error', 'Vous devez être un agent pour soumettre une demande de prospection.');
                return $this->redirectToRoute('app_demande_prospection_new');
            }

            $entityManager->persist($prospection);
            $entityManager->flush();

            // Notification pour approbation
            $this->notificationService->sendApprovalRequest($prospection);

            return $this->redirectToRoute('app_demande_prospection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_prospection/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_prospection_show", methods={"GET"})
     */
    public function show(DemandesProspection $demandesProspection): Response
    {
        return $this->render('demande_prospection/show.html.twig', [
            'demandes_prospection' => $demandesProspection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_demande_prospection_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DemandesProspection $demandesProspection, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        $form = $this->createForm(DemandesProspectionType::class, $demandesProspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Notification pour modification
            $this->notificationService->sendProspectionUpdateNotification($demandesProspection);

            return $this->redirectToRoute('app_demande_prospection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_prospection/edit.html.twig', [
            'form' => $form->createView(),
            'demandes_prospection' => $demandesProspection,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_demande_prospection_delete", methods={"POST"})
     */
    public function delete(Request $request, DemandesProspection $demandesProspection, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$demandesProspection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandesProspection);
            $entityManager->flush();

            // Notification pour suppression
            $this->notificationService->sendProspectionDeletionNotification($demandesProspection);
        }

        return $this->redirectToRoute('app_demande_prospection_index', [], Response::HTTP_SEE_OTHER);
    }
}
