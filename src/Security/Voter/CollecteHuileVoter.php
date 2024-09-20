<?php

// src/Security/Voter/CollecteHuileVoter.php
namespace App\Security\Voter;

use App\Entity\CollectesHuile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CollecteHuileVoter extends Voter
{
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // On vérifie si l'attribut correspond à 'delete' et si le sujet est une instance de CollecteHuile
        return $attribute === self::DELETE && $subject instanceof CollectesHuile;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Obtenir l'utilisateur connecté
        $user = $token->getUser();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            return false;
        }

        // Vérifier si l'utilisateur a le rôle de Super Administrateur
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        // Les autres utilisateurs ne peuvent pas supprimer les collectes d'huile
        return false;
    }
}
