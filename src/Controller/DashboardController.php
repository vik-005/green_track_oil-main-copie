<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(DemandesProspectionRepository $demandesProspectionRepository): Response
    {
        // Appel des méthodes du repository pour récupérer les données nécessaires
       

        // Rendu du template avec les données nécessaires
        return $this->render('admin/dashboard.html.twig', [
            
        ]);
    }
}