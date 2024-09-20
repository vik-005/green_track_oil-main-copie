<?php

namespace App\Controller;

use App\Entity\TypeVendeur;
use App\Form\TypeVendeurType;
use App\Repository\TypeVendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/vendeur")
 */
class TypeVendeurController extends AbstractController
{
    /**
     * @Route("/", name="app_type_vendeur_index", methods={"GET"})
     */
    public function index(TypeVendeurRepository $typeVendeurRepository): Response
    {
        return $this->render('type_vendeur/index.html.twig', [
            'type_vendeurs' => $typeVendeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_type_vendeur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypeVendeurRepository $typeVendeurRepository): Response
    {
        $typeVendeur = new TypeVendeur();
        $form = $this->createForm(TypeVendeurType::class, $typeVendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeVendeurRepository->add($typeVendeur, true);

            return $this->redirectToRoute('app_type_vendeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_vendeur/new.html.twig', [
            'type_vendeur' => $typeVendeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_type_vendeur_show", methods={"GET"})
     */
    public function show(TypeVendeur $typeVendeur): Response
    {
        return $this->render('type_vendeur/show.html.twig', [
            'type_vendeur' => $typeVendeur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_type_vendeur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeVendeur $typeVendeur, TypeVendeurRepository $typeVendeurRepository): Response
    {
        $form = $this->createForm(TypeVendeurType::class, $typeVendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeVendeurRepository->add($typeVendeur, true);

            return $this->redirectToRoute('app_type_vendeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_vendeur/edit.html.twig', [
            'type_vendeur' => $typeVendeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_type_vendeur_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeVendeur $typeVendeur, TypeVendeurRepository $typeVendeurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeVendeur->getId(), $request->request->get('_token'))) {
            $typeVendeurRepository->remove($typeVendeur, true);
        }

        return $this->redirectToRoute('app_type_vendeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
