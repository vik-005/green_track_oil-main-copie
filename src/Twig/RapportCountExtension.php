<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\RapportRepository;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;

class RapportCountExtension extends AbstractExtension
{
    private $security;
    private $rapportRepository;

    public function __construct(Security $security, RapportRepository $rapportRepository)
    {
        $this->security = $security;
        $this->rapportRepository = $rapportRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('waitingRapportCount', [$this, 'getRapportCount']),
        ];
    }

    public function getRapportCount()
    {
        $user = $this->security->getUser();

        // Compter les rapports en fonction du rôle de l'utilisateur
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->rapportRepository->count(['Statut' => 'en_attente']);
        } else {
            return $this->rapportRepository->count([
                'agent' => $user,
                'Statut' => 'en_attente',
            ]);
        }
    }
}
