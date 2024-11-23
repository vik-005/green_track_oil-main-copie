<?php

namespace App\Controller;

use App\Entity\EntreStock;
use App\Form\EntreStockType;
use App\Repository\EntreStockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller pour gérer les Entrées de Stock
 *
 * @Route("/entre/stock")
 */
class EntreStockController extends AbstractController
{
    /**
     * Liste de toutes les entrées de stock
     *
     * @Route("/", name="app_entre_stock_index", methods={"GET"})
     */
    public function index(EntreStockRepository $entreStockRepository): Response
    {
        // Récupère toutes les entrées de stock
        $entreStocks = $entreStockRepository->findAll();

        // Rendu du template avec les données
        return $this->render('entre_stock/index.html.twig', [
            'entre_stocks' => $entreStocks,
        ]);
    }

    /**
     * Créer une nouvelle entrée de stock
     *
     * @Route("/new", name="app_entre_stock_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntreStockRepository $entreStockRepository): Response
    {
        $entreStock = new EntreStock();
        $form = $this->createForm(EntreStockType::class, $entreStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données pour le calcul du total
            $nombreBidons = $entreStock->getNombrebidons();
            $prixUnitaire = $entreStock->getPrixunitaire();

            // Calcul du total (nombre de bidons * prix unitaire)
            $total = $nombreBidons * $prixUnitaire;
            $entreStock->setTotal($total);

            // Ajout de l'entrée dans la base de données
            $entreStockRepository->add($entreStock, true);

            // Notification de succès
            $this->addFlash('success', 'L\'entrée de stock a été ajoutée avec succès.');

            return $this->redirectToRoute('app_entre_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        // En cas d'échec du formulaire
        if ($form->isSubmitted() && !$form->isValid()) {
            // Notification d'erreur
            $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'entrée de stock.');
        }

        // Rendu du formulaire
        return $this->renderForm('entre_stock/new.html.twig', [
            'entre_stock' => $entreStock,
            'form' => $form,
        ]);
    }

    /**
     * Afficher les détails d'une entrée de stock
     *
     * @Route("/{id}", name="app_entre_stock_show", methods={"GET"})
     */
    public function show(EntreStock $entreStock): Response
    {
        // Rendre le template avec les détails de l'entrée de stock
        return $this->render('entre_stock/show.html.twig', [
            'entre_stock' => $entreStock,
        ]);
    }

    /**
     * Modifier une entrée de stock existante
     *
     * @Route("/{id}/edit", name="app_entre_stock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntreStock $entreStock, EntreStockRepository $entreStockRepository): Response
    {
        
        if (
            !$this->isGranted("ROLE_ADMIN") && 
            $entreStock->getNommagasinier() != $this->getUser()
        ) {
            throw $this->createAccessDeniedException();
        }

        // Vérifier que l'utilisateur a le rôle ADMIN pour modifier
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(EntreStockType::class, $entreStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données pour le calcul du total
            $nombreBidons = $entreStock->getNombrebidons();
            $prixUnitaire = $entreStock->getPrixunitaire();

            // Calcul du total (nombre de bidons * prix unitaire)
            $total = $nombreBidons * $prixUnitaire;
            $entreStock->setTotal($total);

            // Mise à jour de l'entrée dans la base de données
            $entreStockRepository->add($entreStock, true);

            // Notification de succès
            $this->addFlash('success', 'L\'entrée de stock a été modifiée avec succès.');

            return $this->redirectToRoute('app_entre_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        // En cas d'échec du formulaire
        if ($form->isSubmitted() && !$form->isValid()) {
            // Notification d'erreur
            $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'entrée de stock.');
        }

        // Rendu du formulaire
        return $this->renderForm('entre_stock/edit.html.twig', [
            'entre_stock' => $entreStock,
            'form' => $form,
        ]);
    }

    /**
     * Supprimer une entrée de stock
     *
     * @Route("/{id}", name="app_entre_stock_delete", methods={"POST"})
     */
    public function delete(Request $request, EntreStock $entreStock, EntreStockRepository $entreStockRepository): Response
    {
        // Vérifier que l'utilisateur a le rôle ADMIN pour supprimer
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Vérifier la validité du token CSRF
        if ($this->isCsrfTokenValid('delete'.$entreStock->getId(), $request->request->get('_token'))) {
            // Supprimer l'entrée de stock
            $entreStockRepository->remove($entreStock, true);

            // Notification de succès
            $this->addFlash('success', 'L\'entrée de stock a été supprimée avec succès.');
        } else {
            // Notification d'erreur si le token CSRF est invalide
            $this->addFlash('error', 'La suppression a échoué.');
        }

        // Redirection vers la liste des entrées de stock
        return $this->redirectToRoute('app_entre_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}