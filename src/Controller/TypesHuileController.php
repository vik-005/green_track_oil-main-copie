<?php

namespace App\Controller;

use App\Entity\TypesHuile;
use App\Form\TypesHuileType;
use App\Repository\TypesHuileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/types/huile")
 */
class TypesHuileController extends AbstractController
{
    /**
     * @Route("/", name="app_types_huile_index", methods={"GET"})
     */
    public function index(TypesHuileRepository $typesHuileRepository): Response
    {
        return $this->render('types_huile/index.html.twig', [
            'types_huiles' => $typesHuileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_types_huile_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypesHuileRepository $typesHuileRepository): Response
    {
        $typesHuile = new TypesHuile();
        $form = $this->createForm(TypesHuileType::class, $typesHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typesHuileRepository->add($typesHuile, true);

            return $this->redirectToRoute('app_types_huile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('types_huile/new.html.twig', [
            'types_huile' => $typesHuile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_types_huile_show", methods={"GET"})
     */
    public function show(TypesHuile $typesHuile): Response
    {
        return $this->render('types_huile/show.html.twig', [
            'types_huile' => $typesHuile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_types_huile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypesHuile $typesHuile, TypesHuileRepository $typesHuileRepository): Response
    {
        $form = $this->createForm(TypesHuileType::class, $typesHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typesHuileRepository->add($typesHuile, true);

            return $this->redirectToRoute('app_types_huile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('types_huile/edit.html.twig', [
            'types_huile' => $typesHuile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_types_huile_delete", methods={"POST"})
     */
    public function delete(Request $request, TypesHuile $typesHuile, TypesHuileRepository $typesHuileRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typesHuile->getId(), $request->request->get('_token'))) {
            $typesHuileRepository->remove($typesHuile, true);
        }

        return $this->redirectToRoute('app_types_huile_index', [], Response::HTTP_SEE_OTHER);
    }
}
