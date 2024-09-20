<?php

namespace App\Controller;

use App\Entity\CollectesHuile;
use App\Form\CollectesHuileType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollectesHuileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/collectes-huile")
 */
class CollectesHuileController extends AbstractController
{
    /**
     * @Route("/", name="collectes_huile_index", methods={"GET"})
     */
    public function index(CollectesHuileRepository $collectesHuileRepository): Response
    {
        return $this->render('collectes_huile/index.html.twig', [
            'collectes' => $collectesHuileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="collectes_huile_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $collecte = new CollectesHuile();
        $form = $this->createForm(CollectesHuileType::class, $collecte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $photoFile = $form->get('photoBidons')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception en cas de problème
                }

                // Stocke le nom du fichier dans l'entité
                $collecte->setPhotoBidons($newFilename);
            }

            $entityManager->persist($collecte);
            $entityManager->flush();

            return $this->redirectToRoute('collectes_huile_index');
        }

        return $this->render('collectes_huile/new.html.twig', [
            'collecte' => $collecte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="collectes_huile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CollectesHuile $collecte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CollectesHuileType::class, $collecte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $photoFile = $form->get('photoBidons')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception en cas de problème
                }

                // Stocke le nom du fichier dans l'entité
                $collecte->setPhotoBidons($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('collectes_huile_index');
        }

        return $this->render('collectes_huile/edit.html.twig', [
            'collecte' => $collecte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collectes_huile_delete", methods={"POST"})
     */
    public function delete(Request $request, CollectesHuile $collecte, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur a la permission de supprimer (grâce au voter)
        $this->denyAccessUnlessGranted('delete', $collecte);

        if ($this->isCsrfTokenValid('delete' . $collecte->getId(), $request->request->get('_token'))) {
            // Supprimer l'image associée si nécessaire
            if ($collecte->getPhotoBidons()) {
                unlink($this->getParameter('photos_directory') . '/' . $collecte->getPhotoBidons());
            }

            $entityManager->remove($collecte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('collectes_huile_index');
    }
}
