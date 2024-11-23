<?php

namespace App\Controller;

use App\Entity\Rapport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/rapport")
 */
class ValiderController extends AbstractController
{
    private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/en-attentes", name="rapport_attente_index", methods={"GET"})
     */
    public function attente(): Response
    {
        $rapports = $this->getRapportsByStatus('en_attente');

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapports,
            'statut' => 'En attente',
        ]);
    }

    /**
     * @Route("/valides", name="rapport_valide_index", methods={"GET"})
     */
    public function valides(): Response
    {
        $rapports = $this->getRapportsNotInStatus('en_attente');

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapports,
            'statut' => 'Validés',
        ]);
    }

    /**
     * @Route("/", name="rapport_index", methods={"GET"})
     */
    public function index(): Response
    {
        $rapports = $this->getAllRapports();

        return $this->render('rapport/index.html.twig', [
            'rapports' => $rapports,
            'tatut' => 'Tous les rapports',
        ]);
    }

    private function getRapportsByStatus(string $status): array
    {
        return $this->entityManager->getRepository(Rapport::class)
            ->findBy(['statut' => $status]);
    }

    private function getRapportsNotInStatus(string $status): array
    {
        return $this->entityManager->getRepository(Rapport::class)
            ->findBy(['statut' => 'validé']); // Remplacez par le statut correspondant aux rapports validés
    }

    private function getAllRapports(): array
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->entityManager->getRepository(Rapport::class)->findAll();
        }
        
        return $this->entityManager->getRepository(Rapport::class)
            ->findBy(['agent' => $this->getUser()]);
    }
}
