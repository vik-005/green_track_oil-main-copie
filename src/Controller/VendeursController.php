<?php

namespace App\Controller;

use App\Entity\Vendeurs;
use App\Form\VendeursType;
use App\Service\PdfService;
use App\Repository\VendeursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/vendeur")
 */
class VendeursController extends AbstractController
{
    private  $entityManager;
    private  $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="app_vendeurs_index", methods={"GET"})
     */
    public function index(VendeursRepository $vendeursRepository): Response
    {
        return $this->render('vendeurs/index.html.twig', [
            'vendeurs' => $vendeursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_vendeurs_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $vendeur = new Vendeurs();
        $form = $this->createForm(VendeursType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processForm($form, $vendeur);
            $this->addFlash('success', 'Vendeur ajouté avec succès.');

            return $this->redirectToRoute('app_vendeurs_index');
        }

        return $this->render('vendeurs/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_vendeurs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, VendeursRepository $vendeursRepository, int $id): Response
    {
        $vendeur = $vendeursRepository->find($id);

        if (!$vendeur) {
            throw $this->createNotFoundException('Vendeur non trouvé.');
        }

        $form = $this->createForm(VendeursType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processForm($form, $vendeur, true);
            $this->addFlash('success', 'Vendeur modifié avec succès.');

            return $this->redirectToRoute('app_vendeurs_index');
        }

        return $this->render('vendeurs/edit.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_vendeurs_delete", methods={"POST"})
     */
    public function delete(Request $request, Vendeurs $vendeur): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vendeur->getId(), $request->request->get('_token'))) {
            $this->deletePhotos($vendeur);
            $this->entityManager->remove($vendeur);
            $this->entityManager->flush();

            $this->addFlash('success', 'Vendeur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'La suppression a échoué.');
        }

        return $this->redirectToRoute('app_vendeurs_index');
    }

    /**
     * @Route("/{id}", name="app_vendeurs_show", methods={"GET"})
     */
    public function show(Vendeurs $vendeur): Response
    {
        return $this->render('vendeurs/show.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }
    /**
     * @Route("/vendeurs/export-pdf", name="app_vendeurs_export_pdf", methods={"GET"})
     */
    public function exportPdf(VendeursRepository $vendeurRepository, PdfService $pdfService): Response
    {
        // Récupérer les données des vendeurs
        $vendeurs = $vendeurRepository->findAll();

        // Générer le contenu HTML pour le PDF
        $html = $this->renderView('vendeurs/pdf.html.twig', [
            'vendeurs' => $vendeurs,
        ]);

        // Générer le fichier PDF
        $pdfContent = $pdfService->createPdf($html);

        // Retourner le PDF en tant que réponse
        return new Response($pdfContent, 300, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="liste_vendeurs.pdf"',
        ]);
    }



    /**
     * Traitement des formulaires et gestion des photos
     */
    private function processForm($form, Vendeurs $vendeur, bool $isEdit = false): void
    {
        $existingPhotos = $isEdit ? $vendeur->getPhoto() : [];
        $newPhotos = $form->get('photo')->getData();

        $vendeur->setPhoto($this->handlePhotos($newPhotos, $existingPhotos));
        $vendeur->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($vendeur);
        $this->entityManager->flush();
    }

    /**
     * Gestion des photos pour un vendeur
     */
    private function handlePhotos(array $newPhotos, array $existingPhotos = []): array
    {
        $photoNames = $existingPhotos;

        foreach ($newPhotos as $photoFile) {
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move($this->getParameter('photos_directory'), $newFilename);
                    $photoNames[] = $newFilename;
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }
        }

        return $photoNames;
    }

    /**
     * Suppression des photos associées
     */
    private function deletePhotos(Vendeurs $vendeur): void
    {
        $photos = $vendeur->getPhoto();
        $photosDirectory = $this->getParameter('photos_directory');

        foreach ($photos as $photo) {
            $photoPath = $photosDirectory . '/' . $photo;
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
    }
}