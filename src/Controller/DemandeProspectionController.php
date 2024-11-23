<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\PdfService;
use App\Entity\DemandesProspection;
use App\Form\DemandesProspectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/prospection")
 */
class DemandeProspectionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/en-attente", name="app_demande_prospection_attente_index", methods={"GET"})
     */
    public function attente(DemandesProspectionRepository $demandesProspectionRepository, Request $request): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $vendorType = $request->query->get('vendor_type');

        // Build filter criteria
        $criteria = ['statut' => 'en_attente'];

        // Vérifiez si les dates sont valides avant de les utiliser
        if ($startDate && $endDate) {
            try {
                $startDateObj = new \DateTime($startDate);
                $endDateObj = new \DateTime($endDate);
                $criteria['dateDemande'] = ['gte' => $startDateObj, 'lte' => $endDateObj];
            } catch (\Exception $e) {
                // Gérer l'erreur de format de date ou ignorer le filtre
                $this->addFlash('error', 'Invalid date format.');
                return $this->redirectToRoute('app_demande_prospection_attente_index');
            }
        }

        // Ajoutez d'autres critères de filtre
        if ($vendorType) {
            $criteria['vendorType'] = $vendorType;
        }

        // Si l'utilisateur a le rôle "ROLE_ADMIN"
        if ($this->isGranted('ROLE_ADMIN')) {
            $demandesProspection = $demandesProspectionRepository->findBy($criteria);
        } else {
            $demandesProspection = $demandesProspectionRepository->findBy([
                'agent' => $this->getUser(),
                'statut' => 'en_attente'
            ]);
        }

        return $this->render('demande_prospection/index.html.twig', [
            'demandes_prospections' => $demandesProspection,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vendor_type' => $vendorType,
        ]);
    }

    /**
     * @Route("/", name="app_demande_prospection_index", methods={"GET"})
     */
    public function index(DemandesProspectionRepository $demandesProspectionRepository, Request $request): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $vendorType = $request->query->get('vendor_type');

        // Build filter criteria
        $criteria = [];

        if ($startDate && $endDate) {
            $criteria['dateDemande'] = ['gte' => new \DateTime($startDate), 'lte' => new \DateTime($endDate)];
        }
        if ($vendorType) {
            $criteria['Typevendeur'] = $vendorType;
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $demandesProspection = $demandesProspectionRepository->findBy($criteria);
        } else {
            $demandesProspection = $demandesProspectionRepository->findBy([
                'agent' => $this->getUser(),
            ]);
        }

        return $this->render('demande_prospection/index.html.twig', [
            'demandes_prospections' => $demandesProspection,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vendor_type' => $vendorType,
        ]);
    }

    /**
     * @Route("/new", name="app_demande_prospection_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $prospection = new DemandesProspection();
        $form = $this->createForm(DemandesProspectionType::class, $prospection);
        $form->handleRequest($request);
        if (!$this->isGranted('ROLE_AGENT')) {
            $this->addFlash('error', 'Vous devez être un agent pour soumettre une collecte d\'huile.');
            return $this->redirectToRoute('app_collectes_huile_index');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Set status and date of request
            $prospection->setStatut('en_attente');

            // Assign the logged-in agent (if authenticated and valid role)
            $agent = $this->getUser();


            // Save the request in the database
            $prospection->setAgent($this->getUser());
            $prospection->setDateDemande(new \DateTime());
            $this->entityManager->persist($prospection);
            $this->entityManager->flush();

            $this->addFlash('success', 'The prospecting request has been successfully created.');
            return $this->redirectToRoute('app_demande_prospection_index');
        }

        return $this->render('demande_prospection/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_prospection_show", methods={"GET"})
     */
    public function show(DemandesProspection $demandesProspection): Response
    {
        return $this->render('demande_prospection/show.html.twig', [
            'demandes_prospection' => $demandesProspection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_demande_prospection_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DemandesProspection $demandesProspection): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Access check

        $form = $this->createForm(DemandesProspectionType::class, $demandesProspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save changes
            $this->entityManager->flush();

            $this->addFlash('success', 'The prospecting request has been updated successfully.');
            return $this->redirectToRoute('app_demande_prospection_index');
        }

        return $this->render('demande_prospection/edit.html.twig', [
            'form' => $form->createView(),
            'demandes_prospection' => $demandesProspection,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_demande_prospection_delete", methods={"POST"})
     */
    public function delete(Request $request, DemandesProspection $demandesProspection): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Access check

        if ($this->isCsrfTokenValid('delete' . $demandesProspection->getId(), $request->request->get('_token'))) {
            // Delete the prospecting request
            $this->entityManager->remove($demandesProspection);
            $this->entityManager->flush();

            $this->addFlash('success', 'The prospecting request has been deleted successfully.');
        }

        return $this->redirectToRoute('app_demande_prospection_index');
    }

    /**
     * @Route("/prospection/export-pdf", name="app_demande_prospection_export_pdf",)
     */
    public function exportPdf(DemandesProspectionRepository $demandesProspectionRepository, PdfService $pdfService): Response
    {
        // Récupérer les données à afficher

        $demandesProspection = $demandesProspectionRepository->findAll();
        // Configurer DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Générer le contenu HTML
        $html = $this->renderView('demande_prospection/export.html.twig', [
            'demandes_prospections' => $demandesProspection,
        ]);

        // Générer le fichier PDF
        $pdfContent = $pdfService->createPdf($html);

        // Retourner le PDF en tant que réponse
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="prospection_requests.pdf"',
        ]);
    }
}