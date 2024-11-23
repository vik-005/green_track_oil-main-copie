<?php

namespace App\Service;

use App\Repository\CollectesHuileRepository;

class StatisticsService
{
private $collectesHuileRepository;

public function __construct(CollectesHuileRepository $collectesHuileRepository)
{
$this->collectesHuileRepository = $collectesHuileRepository;
}

public function getMonthlyCollectionVolumes(): array
{
return $this->collectesHuileRepository->findMonthlyCollectionVolumes();
}

public function getCollectionByOilType(): array
{
return $this->collectesHuileRepository->findCollectionByOilType();
}

public function getCollectionByVendorType(): array
{
return $this->collectesHuileRepository->findCollectionByVendorType();
}
}