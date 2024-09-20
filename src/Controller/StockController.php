<?php

namespace App\Controller;

use App\Entity\SortiesStock;
use App\Form\SortiesStockType;
use App\Repository\SortiesStockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="app_stock_index", methods={"GET"})
     */
    public function index(SortiesStockRepository $sortiesStockRepository): Response
    {
        return $this->render('stock/index.html.twig', [
            'sorties_stocks' => $sortiesStockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_stock_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortiesStockRepository $sortiesStockRepository): Response
    {
        $sortiesStock = new SortiesStock();
        $form = $this->createForm(SortiesStockType::class, $sortiesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortiesStockRepository->add($sortiesStock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'sorties_stock' => $sortiesStock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_stock_show", methods={"GET"})
     */
    public function show(SortiesStock $sortiesStock): Response
    {
        return $this->render('stock/show.html.twig', [
            'sorties_stock' => $sortiesStock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_stock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SortiesStock $sortiesStock, SortiesStockRepository $sortiesStockRepository): Response
    {
        $form = $this->createForm(SortiesStockType::class, $sortiesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortiesStockRepository->add($sortiesStock, true);

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'sorties_stock' => $sortiesStock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_stock_delete", methods={"POST"})
     */
    public function delete(Request $request, SortiesStock $sortiesStock, SortiesStockRepository $sortiesStockRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortiesStock->getId(), $request->request->get('_token'))) {
            $sortiesStockRepository->remove($sortiesStock, true);
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
