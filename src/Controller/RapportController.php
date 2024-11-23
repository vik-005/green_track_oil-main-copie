<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Rapport;
use App\Form\RapportType;
use Symfony\Flex\Options;
use App\Repository\RapportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/rapport")
 */
class RapportController extends AbstractController
{
    private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/en-atten", name="rapport_attente_index", methods={"GET"})
     */
    public function valides(RapportRepository $rapportRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            $rapports = $rapportRepository->findBy([
                'statut' => 'en_attente'
            ]);
        } else {
            $rapports = $rapportRepository->findBy([
                'agent' => $this->getUser(),
                'statut' => 'en_attente'
            ]);
        }

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    /**
     * @Route("/", name="rapport_index", methods={"GET"})
     */
    public function index(RapportRepository $rapportRepository): Response
    {
        $rapports = $this->getRapportsByAgent($rapportRepository);

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    /**
     * @Route("/new", name="rapport_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $rapport = new Rapport();
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent = $this->getUser();

            if ($agent && $this->isGranted('ROLE_AGENT')) {
                $rapport->setAgent($agent);
                $rapport->setStatut('en_attente');
                $rapport->setDateDemande(new \DateTime());

                $this->entityManager->persist($rapport);
                $this->entityManager->flush();

                // Flash message for successful creation
                $this->addFlash('success', 'Le rapport a été créé avec succès.');
                return $this->redirectToRoute('rapport_index');
            }

            // Flash message for error
            $this->addFlash('error', 'Vous devez être un agent pour soumettre un rapport.');
            return $this->redirectToRoute('rapport_new');
        }

        return $this->render('rapport/new.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rapport_show", methods={"GET"})
     */
    public function show(Rapport $rapport): Response
    {
        return $this->render('rapport/show.html.twig', [
            'rapport' => $rapport,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rapport_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rapport $rapport): Response
    {
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            // Flash message for successful edit
            $this->addFlash('success', 'Le rapport a été modifié avec succès.');
            return $this->redirectToRoute('rapport_index');
        }

        return $this->render('rapport/edit.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="rapport_delete", methods={"POST"})
     */
    public function delete(Request $request, Rapport $rapport): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rapport->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($rapport);
            $this->entityManager->flush();

            // Flash message for successful deletion
            $this->addFlash('success', 'Le rapport a été supprimé avec succès.');
        }

        return $this->redirectToRoute('rapport_index');
    }

    private function getRapportsByStatut(RapportRepository $rapportRepository, string $statut)
    {
        return $this->isGranted('ROLE_ADMIN')
            ? $rapportRepository->findBy(['statut' => $statut])
            : $rapportRepository->findBy(['agent' => $this->getUser(), 'statut' => $statut]);
    }

    private function getRapportsByAgent(RapportRepository $rapportRepository)
    {
        return $this->isGranted('ROLE_ADMIN')
            ? $rapportRepository->findAll()
            : $rapportRepository->findBy(['agent' => $this->getUser()]);
    }



    public function exportToPdf(RapportRepository $rapportRepository): Response
    {
        $rapports = $rapportRepository->findAll();

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Rendu du template
        $html = $this->renderView('rapport/export_pdf.html.twig', [
            'rapports' => $rapports,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Format paysage
        $dompdf->render();

        // Génération du fichier PDF en téléchargement
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="rapports.pdf"',
        ]);
    }
}