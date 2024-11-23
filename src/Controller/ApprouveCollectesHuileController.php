<?php

namespace App\Controller;

use App\Entity\CollectesHuile;
use App\Entity\EntreStock; 
use App\Form\ApproveCollectesHuileType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollectesHuileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/collectes/huile")
 */
class ApprouveCollectesHuileController extends AbstractController
{
    /**
     * @Route("/valider", name="app_collectes_huile_valider_index", methods={"GET"})
     */
    public function attente(CollectesHuileRepository $collectesHuileRepository): Response
    {
        // Récupérer toutes les collectes d'huile en attente d'approbation
        $collectesHuile = $collectesHuileRepository->findBy(['statut' => 'en_attente']);

        return $this->render('collectes_huile/index.html.twig', [
            'collectes_huiles' => $collectesHuile,
        ]);
    }

    /**
     * @Route("/valider/{id}/edit", name="app_collectes_huile_valider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CollectesHuile $collectesHuile, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire d'approbation de la collecte d'huile
        $form = $this->createForm(ApproveCollectesHuileType::class, $collectesHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le magasinier change le statut de la collecte, enregistrer son nom
            if ($collectesHuile->getStatut() === 'approuve') {
                // Si le statut devient "approuvé", effectuer la mise à jour du stock
                $this->updateStock($collectesHuile, $entityManager);
            }
            $collectesHuile->setNomMagasinier($this->getUser());
            // Sauvegarder les changements dans la base de données
            $entityManager->flush();

            // Rediriger vers la page des collectes en attente après modification
            return $this->redirectToRoute('app_collectes_huile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collectes_huile/edit.html.twig', [
            'collectes_huile' => $collectesHuile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Méthode pour mettre à jour le stock basé sur la collecte d'huile
     */
    private function updateStock(CollectesHuile $collectesHuile, EntityManagerInterface $entityManager)
    {
        // Créer une nouvelle entrée dans le stock basée sur la collecte d'huile
        $entreeStock = new EntreStock();

        // Assigner les informations pertinentes de la collecte d'huile à l'entrée de stock
        $entreeStock->setVendeur($collectesHuile->getVendeurs()); // Assurez-vous que le vendeur est correctement défini dans la collecte
        $entreeStock->setNombrebidons($collectesHuile->getVolume());
        $entreeStock->setPrixunitaire($collectesHuile->getPrixAchat());
        $entreeStock->setTypehuile($collectesHuile->getTypehuile());
        $entreeStock->setDateEnregisterement(new \DateTime());
        $entreeStock->setAgent($collectesHuile->getUtilisateurs());
        $entreeStock->setNommagasinier($this->getUser());
   // Enregistrer la date d'entrée
        $entreeStock->setTotal($collectesHuile->getVolume() * $collectesHuile->getPrixAchat()); // Calcul du total

        // Sauvegarder l'entrée dans le stock
        $entityManager->persist($entreeStock);
        $entityManager->flush();
    }
}