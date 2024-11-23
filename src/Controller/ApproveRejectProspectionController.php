<?php

namespace App\Controller;

use DateTime;
use App\Entity\DemandesProspection;
use App\Form\DemandesProspectionAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/prospection")
 */
class ApproveRejectProspectionController extends AbstractController
{
    private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/en_attente", name="app_demande_prospection_attente_index", methods={"GET"})
     */
    public function index(DemandesProspectionRepository $demandesProspectionRepository): Response
    {
        $demandesProspection = $demandesProspectionRepository->findAll();

        return $this->render('demande_prospection/index.html.twig', [
            'demandes_prospections' => $demandesProspection,
        ]);
    }

    /**
     * @Route("en_attente/{id}/edit", name="app_demande_prospection_attente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DemandesProspection $demandesProspection): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Vérification d'accès

        $form = $this->createForm(DemandesProspectionAdminType::class, $demandesProspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandesProspection->setDateApprobation(new DateTime());
            // Sauvegarder les modifications
            $this->entityManager->flush();

            $this->addFlash('success', 'The prospecting request has been processed.');
            return $this->redirectToRoute('app_demande_prospection_attente_index');
        }

        // Gestion d'erreur si des champs sont manquants ou incorrects
        if (!$demandesProspection->getAgent()) {
            $this->addFlash('error', 'The "Supervisor" field is required.');
            return $this->render('demande_prospection/edit.html.twig', [
                'form' => $form->createView(),
                'demandes_prospection' => $demandesProspection,
            ]);
        }

        return $this->render('demande_prospection/edit.html.twig', [
            'form' => $form->createView(),
            'demandes_prospection' => $demandesProspection,
        ]);
    }
}