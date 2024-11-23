<?php

// src/Controller/SortiesStockController.php

namespace App\Controller;

use App\Entity\SortiesStock;
use App\Service\StockService;
use App\Form\SortiesStockType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\ByteString;
use App\Repository\SortiesStockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/sorties-stock")
 */
class SortiesStockController extends AbstractController
{
    private $stockService;

    
    /**
     * @Route("/", name="sorties_stock_index", methods={"GET"})
     */
    public function index(SortiesStockRepository $sortiesStockRepository): Response
    {
        return $this->render('sorties_stock/index.html.twig', [
            'sorties_stocks' => $sortiesStockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sorties_stock_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $sortiesStock = new SortiesStock();
        $form = $this->createForm(SortiesStockType::class, $sortiesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de la photo
            $photoFiles = $form->get('photo')->getData();
            $uploadedFiles = [];
            foreach ($photoFiles as $photoFile) {
                if ($photoFile) {
                    $photoFileName = $photoFile->getClientOriginalName();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $photoFileName
                    );
                    $uploadedFiles[ByteString::fromRandom(5)->toString()] = $photoFileName;
                }
            }
            $sortiesStock->setPhoto(json_encode($uploadedFiles));

            // Associer le nom de l'utilisateur connecté
            $sortiesStock->setNommag($this->getUser());
            $sortiesStock->setDateSorti(new \DateTime()); // Définit la date de sortie

           

            // Enregistrer les sorties de stock
            $em->persist($sortiesStock);
            $em->flush();

            return $this->redirectToRoute('sorties_stock_index');
        }

        return $this->render('sorties_stock/new.html.twig', [
            'sorties_stock' => $sortiesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sorties_stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SortiesStock $sortiesStock, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SortiesStockType::class, $sortiesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de la photo
            $photoFiles = $form->get('photo')->getData();
            $uploadedFiles = json_decode($sortiesStock->getPhoto() ?? '[]', true);
            foreach ($photoFiles as $photoFile) {
                if ($photoFile) {
                    $photoFileName = $photoFile->getClientOriginalName();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $photoFileName
                    );
                    $uploadedFiles[ByteString::fromRandom(5)->toString()] = $photoFileName;
                }
            }
            $sortiesStock->setPhoto(json_encode($uploadedFiles));

            $em->flush();

            return $this->redirectToRoute('sorties_stock_index');
        }

        return $this->render('sorties_stock/edit.html.twig', [
            'sorties_stock' => $sortiesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sorties_stock_show", methods={"GET"})
     */
    public function show(SortiesStock $sortiesStock): Response
    {
        return $this->render('sorties_stock/show.html.twig', [
            'sorties_stock' => $sortiesStock,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="sorties_stock_delete", methods={"POST"})
     */
    public function delete(Request $request, SortiesStock $sortiesStock, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortiesStock->getId(), $request->request->get('_token'))) {
            $em->remove($sortiesStock);
            $em->flush();
        }

        return $this->redirectToRoute('sorties_stock_index');
    }
}