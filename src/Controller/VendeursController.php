<?php

// src/Controller/VendeursController.php
namespace App\Controller;

use App\Entity\Vendeurs;
use App\Form\VendeursType;
use App\Repository\VendeursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/vendeur")
 */
class VendeursController extends AbstractController
{
    /**
     * @Route("/", name="vendeur_index", methods={"GET"})
     */
    public function index(VendeursRepository $vendeurRepository): Response
    {
        return $this->render('vendeur/index.html.twig', [
            'vendeurs' => $vendeurRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="vendeur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $vendeur = new Vendeurs();
        $form = $this->createForm(VendeursType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vendeur);
            $em->flush();

            return $this->redirectToRoute('vendeur_index');
        }

        return $this->render('vendeur/new.html.twig',[
            'vendeur' => $vendeur, // Correction ici
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vendeur_edit", methods={"GET", "POST"})
     */
    
    public function edit(Request $request, VendeursRepository $vendeursRepository, int $id, EntityManagerInterface $em): Response
    {
    $vendeur = $vendeursRepository->find($id);

    if (!$vendeur) {
        throw $this->createNotFoundException('Vendeur non trouvé');
    }

    $form = $this->createForm(VendeursType::class, $vendeur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        return $this->redirectToRoute('vendeur_index');
    }

    return $this->render('vendeur/edit.html.twig', [
        'vendeur' => $vendeur,
        'form' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/delete", name="vendeur_delete", methods={"POST"})
     */
    public function delete(Request $request, Vendeurs $vendeur, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vendeur->getId(), $request->request->get('_token'))) {
            $em->remove($vendeur);
            $em->flush();
        }

        return $this->redirectToRoute('vendeur_index');
    }
}
