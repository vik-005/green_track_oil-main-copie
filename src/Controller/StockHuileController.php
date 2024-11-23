<?php

namespace App\Controller;

use App\Entity\StockHuile;
use App\Form\StockHuileType;
use App\Repository\StockHuileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stocks/huile")
 */
class StockHuileController extends AbstractController
{
    /**
     * @Route("/", name="app_stock_huile_index", methods={"GET"})
     */
    public function index(StockHuileRepository $stockHuileRepository): Response
    {
        return $this->render('stock_huile/index.html.twig', [
            'stock_huiles' => $stockHuileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_stock_huile_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stockHuile = new StockHuile();
        $form = $this->createForm(StockHuileType::class, $stockHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockHuile->setDateMaj(new \DateTime()); // Ajoute la date actuelle
            $entityManager->persist($stockHuile);
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_huile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_huile/new.html.twig', [
            'stock_huile' => $stockHuile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_stock_huile_show", methods={"GET"})
     */
    public function show(StockHuile $stockHuile): Response
    {
        return $this->render('stock_huile/show.html.twig', [
            'stock_huile' => $stockHuile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_stock_huile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, StockHuile $stockHuile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockHuileType::class, $stockHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockHuile->setDateMaj(new \DateTime()); // Met à jour la date de modification
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_huile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_huile/edit.html.twig', [
            'stock_huile' => $stockHuile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_stock_huile_delete", methods={"POST"})
     */
    public function delete(Request $request, StockHuile $stockHuile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockHuile->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stockHuile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stock_huile_index', [], Response::HTTP_SEE_OTHER);
    }
}