<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\StockHuile;
use App\Entity\CollectesHuile;
use App\Entity\TypesHuile;
use App\Form\CollectesHuileType;
use App\Repository\StockHuileRepository;
use App\Repository\TypesHuileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollectesHuileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



/**
 * @Route("/collectes/huile")
 */
class CollectesHuileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/em_attente", name="app_collectes_huile_attente_index", methods={"GET"})
     */
    public function attente(CollectesHuileRepository $collectesHuileRepository): Response
    {
        $collectesHuiles = $this->isGranted('ROLE_MAGASINIER')
            ? $collectesHuileRepository->findBy(['statut' => 'en_attente'])
            : $collectesHuileRepository->findBy(['utilisateurs' => $this->getUser(), 'statut' => 'en_attente']);

        return $this->render('collectes_huile/index.html.twig', [
            'collectes_huiles' => $collectesHuiles,
        ]);
    }

    /**
     * @Route("/", name="app_collectes_huile_index", methods={"GET"})
     */
    public function index(CollectesHuileRepository $collectesHuileRepository): Response
    {
        $collectesHuiles = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MAGASINIER')
            ? $collectesHuileRepository->findAll()
            : $collectesHuileRepository->findBy(['utilisateurs' => $this->getUser()]);

        return $this->render('collectes_huile/index.html.twig', [
            'collectes_huiles' => $collectesHuiles,
        ]);
    }


    /**
     * @Route("/new", name="app_collectes_huile_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $collectesHuile = new CollectesHuile();
        $form = $this->createForm(CollectesHuileType::class, $collectesHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collectesHuile->setUtilisateurs($this->getUser());
            $collectesHuile->setDateCollecte(new \DateTime());
            $collectesHuile->setStatut('en_attente');

            // Gestion des fichiers de photos
            $uploadedFiles = $form->get('photoBidons')->getData();
            $photoPaths = [];
            if ($uploadedFiles) {
                foreach ($uploadedFiles as $uploadedFile) {
                    try {
                        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                        $uploadedFile->move($this->getParameter('photos_directory'), $newFilename);
                        $photoPaths[] = $newFilename;
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors du téléchargement des fichiers.');
                        return $this->redirectToRoute('app_collectes_huile_new');
                    }
                }
                $collectesHuile->setPhotoBidons($photoPaths); // Stocke les chemins des photos
            }

            $this->entityManager->persist($collectesHuile);
            $this->entityManager->flush();

            $this->addFlash('success', 'Collecte d\'huile ajoutée avec succès.');
            return $this->redirectToRoute('app_collectes_huile_index');
        }

        return $this->render('collectes_huile/new.html.twig', [
            'collectes_huile' => $collectesHuile,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="app_collectes_huile_show", methods={"GET"})
     */
    public function show(CollectesHuile $collectesHuile): Response

    {

        return $this->render('collectes_huile/show.html.twig', [
            'collectes_huile' => $collectesHuile,
        ]);
    }


    /**
     * @Route("/collectes/filtre", name="app_collectes_huile_filter", methods={"GET", "POST"})
     */
    public function filter(
        Request $request,
        CollectesHuileRepository $collectesHuileRepository,
        TypesHuileRepository $typeHuileRepository
    ): Response {
        // Récupération des paramètres de filtre
        $filter = $request->query->get('filter', '');
        $dateStart = $request->query->get('date_start');
        $dateEnd = $request->query->get('date_end');
        $typeHuileId = $request->query->getInt('type_huile');


        // Appliquer les filtres

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MAGASINIER')) {
            $filteredCollectesHuiles = $collectesHuileRepository->searchQuery($request);
        } else {
            $filteredCollectesHuiles = $collectesHuileRepository->searchQuery($request, $this->getUser());
        }

        // Récupérer tous les types d'huile pour le menu déroulant
        $typesHuile = $typeHuileRepository->findAll();

        $totalsByType = [];

        foreach ($typesHuile as $v) {

            $totalsByType[$v->getNomTypeHuile()] = [
                "target" => $v,
                "volume" => array_reduce($filteredCollectesHuiles, function (?float $acc, CollectesHuile $el) use ($v) {
                    if ($el->getTypehuile() == $v) {
                        $acc += $el->getVolume();
                    }
                    return $acc;
                }),
                "montant" => array_reduce($filteredCollectesHuiles, function (?float $acc, CollectesHuile $el) use ($v) {
                    if ($el->getTypehuile() == $v) {
                        $acc += $el->getPrixAchat();
                    }
                    return $acc;
                })
            ];
        }

        return $this->render('collectes_huile/filter.html.twig', [
            'collectes_huiles' => $filteredCollectesHuiles,
            'totals_by_type' => $totalsByType,
            'filter' => $filter,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'type_huile' => $typeHuileId,
            'types_huile' => $typesHuile,
        ]);
    }

    /**
     * Récupère les collectes accessibles à l'utilisateur actuel.
     */
    private function getAccessibleCollectes(CollectesHuileRepository $collectesHuileRepository): array
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MAGASINIER')) {
            return $collectesHuileRepository->findAll();
        }

        return $collectesHuileRepository->findBy(['utilisateurs' => $this->getUser()]);
    }

    /**
     * Applique les filtres aux collectes.
     */
    private function applyFilters(array $collectes, ?string $filter, ?string $dateStart, ?string $dateEnd, ?int $typeHuileId): array
    {
        return array_filter($collectes, function ($collecte) use ($filter, $dateStart, $dateEnd, $typeHuileId) {
            if ($filter && $collecte->getStatut() !== $filter) {
                return false;
            }
            if ($dateStart && $collecte->getDateCollecte() < new \DateTime($dateStart)) {
                return false;
            }
            if ($dateEnd && $collecte->getDateCollecte() >= new \DateTime($dateEnd)) {
                return false;
            }
            if ($typeHuileId && $collecte->getTypeHuile()->getId() != $typeHuileId) {
                return false;
            }
            return true;
        });
    }

    /**
     * Calcule les totaux par type d'huile.
     */
    private function calculateTotalsByType(array $collectes): array
    {
        $totalsByType = [];

        foreach ($collectes as $collecte) {
            $type = $collecte->getTypeHuile()->getNomTypeHuile();
            if (!isset($totalsByType[$type])) {
                $totalsByType[$type] = ['volume' => 0, 'prixAchat' => 0];
            }
            $totalsByType[$type]['volume'] += $collecte->getVolume();
            $totalsByType[$type]['prixAchat'] += $collecte->getPrixAchat();
        }

        return $totalsByType;
    }

    /**
     * Génère un texte lisible des totaux par type d'huile.
     */
    private function generateTotalsText(array $totalsByType): string
    {
        $totalsText = '';

        foreach ($totalsByType as $type => $totals) {
            $totalsText .= sprintf(
                "%s - Volume total : %.2f L, Prix total : %.2f FCFA<br>",
                $type,
                $totals['volume'],
                $totals['prixAchat']
            );
        }

        return $totalsText;
    }
    /**
     * @Route("/{id}/edit", name="app_collectes_huile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CollectesHuile $collectesHuile): Response
    {
        $form = $this->createForm(CollectesHuileType::class, $collectesHuile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_MAGASINIER') && $collectesHuile->getStatut() !== 'en_attente') {
                $collectesHuile->setModifier($this->getUser()->getUsername());
            }

            if ($collectesHuile->getStatut() === 'Approuve') {
                $this->updateStock($collectesHuile);
            }
            $uploadedFiles = $form->get('photoBidons')->getData();
            $photoPaths = [];
            if ($uploadedFiles) {
                foreach ($uploadedFiles as $uploadedFile) {
                    try {
                        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                        $uploadedFile->move($this->getParameter('photos_directory'), $newFilename);
                        $photoPaths[] = $newFilename;
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors du téléchargement des fichiers.');
                        return $this->redirectToRoute('app_collectes_huile_new');
                    }
                }
                $collectesHuile->setPhotoBidons($photoPaths); // Stocke les chemins des photos
            }
            $this->entityManager->flush();
            $this->addFlash('success', 'Collecte d\'huile mise à jour avec succès.');

            return $this->redirectToRoute('app_collectes_huile_index');
        }

        return $this->render('collectes_huile/edit.html.twig', [
            'collectes_huile' => $collectesHuile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_collectes_huile_delete", methods={"POST"})
     */
    public function delete(Request $request, CollectesHuile $collectesHuile): Response
    {
        if ($this->isCsrfTokenValid('delete' . $collectesHuile->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($collectesHuile);
            $this->entityManager->flush();
            $this->addFlash('success', 'Collecte d\'huile supprimée avec succès.');
        }

        return $this->redirectToRoute('app_collectes_huile_index');
    }
    /**
     * @Route("/collectes_huile/{id}/pdf", name="app_collectes_huile_pdf")
     */
    public function generatePdf(int $id): Response
    {
        // Récupérer les détails de la collecte
        $collecte = $this->getDoctrine()->getRepository(CollectesHuile::class)->find($id);

        if (!$collecte) {
            throw $this->createNotFoundException('Collecte non trouvée');
        }

        // Options pour Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le HTML depuis un template Twig
        $html = $this->renderView('collectes_huile/pdf.html.twig', [
            'collecte' => $collecte,
        ]);

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Options de rendu
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();







        // Générer la réponse PDF
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="collecte_huile_' . $id . '.pdf"',
        ]);
    }
}