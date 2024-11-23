<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="app_home")
     */
    public function home(Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }
}