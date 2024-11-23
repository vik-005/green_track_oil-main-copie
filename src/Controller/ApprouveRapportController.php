<?php

namespace App\Controller;
use \DateTime;
use App\Entity\Rapport;
use App\Form\RapportType;
use App\Form\RapportAdminType;
use App\Repository\RapportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/rapport")
 */
class ApprouveRapportController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/en_atten", name="rapport_attente_index", methods={"GET"})
     */
    public function attente(RapportRepository $rapportRepository): Response
    {
        // Filtrer les rapports par statut "en_attente"
        $rapportsEnAttente = $rapportRepository->findBy(['Statut' => 'en_attente']);

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapportsEnAttente,
        ]);
    }

    /**
     * @Route("/en_attentes/{id}/edit", name="rapport_attente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rapport $rapport): Response
    {
        // Vérification d'accès : seul un utilisateur avec le rôle "ROLE_ADMIN" peut accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Créer le formulaire pour modifier le rapport
        $form = $this->createForm(RapportAdminType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapport->setDateApprobation(new DateTime());
            
            // Si des champs obligatoires sont manquants, on gère l'erreur
           
                return $this->render('rapport/edit.html.twig', [
                    'rapport' => $rapport,
                    'form' => $form->createView(),
                ]);
            

            // Sauvegarder les modifications dans la base de données
            $this->entityManager->flush();

            // Ajouter un message flash pour signaler que l'opération a réussi
            $this->addFlash('success', 'Le rapport a été modifié avec succès.');

            // Redirection vers la liste des rapports en attente
            return $this->redirectToRoute('rapport_attente_index');
        }

        return $this->render('rapport/edit.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }
}