<?php

namespace App\Controller;

use App\Repository\CollectesHuileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/statistique")
 */
class StatistiqueController extends AbstractController
{
    /**
     * @Route("/", name="app_statistique_index", methods={"GET"})
     */
    public function index(CollectesHuileRepository $collectesHuileRepository, DemandesProspectionRepository $demandesProspectionRepository): Response
    {
        // Récupérer les statistiques depuis le repository
        $totalOilPerMonth = $collectesHuileRepository->findTotalOilCollectedPerMonth();
        $oilPerAgent = $collectesHuileRepository->findOilCollectedPerAgent();
        $oilPerVendorType = $collectesHuileRepository->findOilCollectedPerVendorType();
        $totalOilPerYear = $collectesHuileRepository->findTotalOilCollectedPerYear();
        $totalCollectionsPerMonth = $collectesHuileRepository->findTotalCollectionsPerMonth();
        $totalVolumeCollected = $collectesHuileRepository->getTotalVolumeCollected();
        $topClients = $collectesHuileRepository->getTopClients();

        // Récupérer les statistiques de demandes de prospection
        $approvedRequests = $demandesProspectionRepository->findApprovedRequests();
        $recentRequests = $demandesProspectionRepository->findRecentRequests();
        $countByStatus = $demandesProspectionRepository->countByStatus();

        return $this->render('statistique/index.html.twig', [
            'total_oil_per_month' => $totalOilPerMonth,
            'oil_per_agent' => $oilPerAgent,
            'oil_per_vendor_type' => $oilPerVendorType,
            'total_oil_per_year' => $totalOilPerYear,
            'total_collections_per_month' => $totalCollectionsPerMonth,
            'total_volume_collected' => $totalVolumeCollected,
            'top_clients' => $topClients,
            'approved_requests' => $approvedRequests,
            'recent_requests' => $recentRequests,
            'count_by_status' => $countByStatus,
        ]);
    }
}