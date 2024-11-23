<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Stock;
use App\Form\StockType;
use App\Entity\StockHuile;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(StockRepository $stockRepository): Response
    {
        return $this->render('stock/index.html.twig', [
            'stocks' => $stockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_stock_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        $stock->setNommagasinier($this->getUser());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_stock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/ajouter", name="app_stock_ajouter", methods={"POST"})
     */
    public function ajouter(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $quantite = $request->request->get('quantite');

        if ($quantite) {
            $stock->setQuantiteStockee($stock->getQuantiteStockee() + $quantite);
            $entityManager->flush();
            $this->addFlash('success', 'Quantité ajoutée au stock.');
        } else {
            $this->addFlash('error', 'Quantité invalide.');
        }

        return $this->redirectToRoute('app_stock_index');
    }

    /**
     * @Route("/{id}/retirer", name="app_stock_retirer", methods={"POST"})
     */
    public function retirer(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $quantite = $request->request->get('quantite');

        if ($quantite && $stock->getQuantiteStockee() >= $quantite) {
            $stock->setQuantiteStockee($stock->getQuantiteStockee() - $quantite);
            $entityManager->flush();
            $this->addFlash('success', 'Quantité retirée du stock.');
        } else {
            $this->addFlash('error', 'Quantité insuffisante en stock ou invalide.');
        }

        return $this->redirectToRoute('app_stock_index');
    }

    /**
     * @Route("/{id}", name="app_stock_show", methods={"GET"})
     */
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/rapport/pdf", name="app_stock_rapport_pdf", methods={"GET"})
     */
    public function generateStockReportPdf(EntityManagerInterface $entityManager): Response
    {
        // Récupération des données de stock
        $stocks = $entityManager->getRepository(StockHuile::class)->findAll();

        // Configuration de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('stock/report_pdf.html.twig', [
            'stocks' => $stocks,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Générer le fichier PDF
        $filename = 'rapport_stock_' . date('Y-m-d') . '.pdf';
        $output = $dompdf->output();
        file_put_contents($this->getParameter('kernel.project_dir') . '/public/uploads/' . $filename, $output);

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
}
