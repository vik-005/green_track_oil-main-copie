<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\AccountSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountSettingsController extends AbstractController
{
    /**
     * @Route("/profil/parametres", name="app_profil_parametres")
     *
     * 
     */
   
    public function parametres(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupère l'utilisateur connecté
        $utilisateur = $this->getUser();

        // Crée le formulaire pour modifier les paramètres du compte
        $form = $this->createForm(AccountSettingsType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie le mot de passe actuel
            $currentPassword = $form->get('currentPassword')->getData();
            if (!$passwordEncoder->isPasswordValid($utilisateur, $currentPassword)) {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                return $this->redirectToRoute('app_profil_parametres');
            }

            // Vérifie que le nouveau mot de passe est confirmé correctement
            $newPassword = $form->get('newPassword')->getData();
            $confirmNewPassword = $form->get('confirmNewPassword')->getData();
            if ($newPassword !== $confirmNewPassword) {
                $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_profil_parametres');
            }

            // Encode et met à jour le nouveau mot de passe
            if ($newPassword) {
                $encodedPassword = $passwordEncoder->encodePassword($utilisateur, $newPassword);
                $utilisateur->setPassword($encodedPassword);
            }

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Paramètres du compte mis à jour avec succès.');

            return $this->redirectToRoute('app_profil_parametres');
        }

        return $this->render('compte/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}