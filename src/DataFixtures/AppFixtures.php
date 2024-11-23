<?php

namespace App\DataFixtures;

use App\Entity\Utilisateurs; // Assurez-vous que cette entité existe
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private  $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur "super administrateur"
        $superAdmin = new Utilisateurs();
        $superAdmin->setNomUtilisateur('admin');
        $superAdmin->setPrenomUtilisateur('admin');
        $superAdmin->setEmail('superadmin@example.com');
        $superAdmin->setRoles(['ROLE_ROOT']);
        
        // Hachage et définition du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin, 'superadmin123');
        $superAdmin->setPassword($hashedPassword);

        // Persist de l'utilisateur
        $manager->persist($superAdmin);

        // Sauvegarde dans la base de données
        $manager->flush();
    }
}