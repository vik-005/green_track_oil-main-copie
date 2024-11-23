<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\Repository;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;
use App\Repository\DemandesProspectionRepository;



class TwigFunctionExtension extends AbstractExtension
{

    private $security;
    private $demandesProspectionRepository;

    public function __construct(
        Security $security,
        DemandesProspectionRepository $demandesProspectionRepository
    ) {
        $this->security = $security;
        $this->demandesProspectionRepository = $demandesProspectionRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('waitingProspectionCount', [$this, 'getProspectionCount'])
        ];
    }

    public function getProspectionCount() {

        $user = $this->security->getUser();


        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->demandesProspectionRepository->count([
                'statut' => 'en_attente'
            ]);
        } else {
            return $this->demandesProspectionRepository->count([
                'agent' => $user,
                'statut' => 'en_attente'
            ]);
        }
        
    }
}
