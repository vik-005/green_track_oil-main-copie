<?php

namespace App\Controller;

use App\Entity\Mouvementstock;
use App\Form\MouvementstockType;
use App\Repository\MouvementstockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/mouvementstock")
 */
class MouvementstockController extends AbstractController
{
    /**
     * @Route("/", name="app_mouvementstock_index", methods={"GET"})
     */
    public function index(MouvementstockRepository $mouvementstockRepository): Response
    {
        return $this->render('mouvementstock/index.html.twig', [
            'mouvementstocks' => $mouvementstockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_mouvementstock_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MouvementstockRepository $mouvementstockRepository): Response
    {
        $mouvementstock = new Mouvementstock();
        $form = $this->createForm(MouvementstockType::class, $mouvementstock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mouvementstockRepository->add($mouvementstock, true);

            return $this->redirectToRoute('app_mouvementstock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mouvementstock/new.html.twig', [
            'mouvementstock' => $mouvementstock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_mouvementstock_show", methods={"GET"})
     */
    public function show(Mouvementstock $mouvementstock): Response
    {
        return $this->render('mouvementstock/show.html.twig', [
            'mouvementstock' => $mouvementstock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_mouvementstock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Mouvementstock $mouvementstock, MouvementstockRepository $mouvementstockRepository): Response
    {
        $form = $this->createForm(MouvementstockType::class, $mouvementstock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mouvementstockRepository->add($mouvementstock, true);

            return $this->redirectToRoute('app_mouvementstock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mouvementstock/edit.html.twig', [
            'mouvementstock' => $mouvementstock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_mouvementstock_delete", methods={"POST"})
     */
    public function delete(Request $request, Mouvementstock $mouvementstock, MouvementstockRepository $mouvementstockRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mouvementstock->getId(), $request->request->get('_token'))) {
            $mouvementstockRepository->remove($mouvementstock, true);
        }

        return $this->redirectToRoute('app_mouvementstock_index', [], Response::HTTP_SEE_OTHER);
    }
}
