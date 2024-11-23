<?php
namespace App\Controller\Admin;

use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
private $statisticsService;

public function __construct(StatisticsService $statisticsService)
{
$this->statisticsService = $statisticsService;
}

/**
* @Route("/admin/statistics/monthly-collections", name="admin_statistics_monthly_collections")
*/
public function monthlyCollections(): JsonResponse
{
$data = $this->statisticsService->getMonthlyCollectionVolumes();
return $this->json($data);
}

/**
* @Route("/admin/statistics/oil-type", name="admin_statistics_oil_type")
*/
public function collectionByOilType(): JsonResponse
{
$data = $this->statisticsService->getCollectionByOilType();
return $this->json($data);
}

/**
* @Route("/admin/statistics/vendor-type", name="admin_statistics_vendor_type")
*/
public function collectionByVendorType(): JsonResponse
{
$data = $this->statisticsService->getCollectionByVendorType();
return $this->json($data);
}
}