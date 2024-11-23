<?php

// src/Form/UtilisateurType.php
namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomUtilisateur', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour le style
                    'placeholder' => 'Entrez votre nom d\'utilisateur', // Placeholder
                ],
            ])
            ->add('prenomutilisateur', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour le style
                    'placeholder' => 'Entrez votre prénom', // Placeholder
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false, // Pas besoin de mapper cette donnée directement
                'attr' => [
                    'class' => 'form-control-file', // Classe Bootstrap pour le style de fichier
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour',
                'attr' => [
                    'class' => 'btn btn-primary mt-3', // Classe Bootstrap pour le bouton
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
