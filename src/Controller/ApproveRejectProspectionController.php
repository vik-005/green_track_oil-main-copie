<?php

namespace App\Controller;

use App\Entity\DemandesProspection;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ApproveRejectProspectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApproveRejectProspectionController extends AbstractController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/prospection/{id}/approve-reject', name: 'prospection_approve_reject')]
    public function approveOrReject(Request $request, DemandesProspection $prospection, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ApproveRejectProspectionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->get('action')->getData();
            $prospection->setStatut($action);
            $prospection->setDateApprobation(new \DateTime());

            if ($action === 'rejeté') {
                $prospection->setCommentaire($form->get('commentaire')->getData());
            }

            $entityManager->flush();

            $this->notificationService->notifyAgentOfProspectionResult($prospection);

            return $this->redirectToRoute('prospection_list');
        }

        return $this->render('prospection/approve_reject.html.twig', [
            'form' => $form->createView(),
            'prospection' => $prospection,
        ]);
    }
}
