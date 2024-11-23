<?php

namespace App\Form;

use App\Entity\TypeVendeur;
use App\Entity\DemandesProspection;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandesProspectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champs pour le pays
            ->add('pays', ChoiceType::class, [
                'choices' =>  array_flip(Countries::getNames()),
                'label' => 'Pays',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le pays',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4 col-md-6 col-12'
                ]
            ])

            // Champs pour la ville
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la ville',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4 col-md-6 col-12'
                ]
            ])

            // Champs pour la région
            ->add('region', TextType::class, [
                'label' => 'Région',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la région',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4 col-md-6 col-12'
                ]
            ])

            // Champs du montant
            ->add('montant', TextType::class, [
                'label' => 'Coût du transport',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le coût du transport',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4 col-md-6 col-12'
                ]
            ])

            // Sélection du type de vendeur
            ->add('typevendeur', EntityType::class, [
                'class' => TypeVendeur::class,
                'choice_label' => 'nomType',
                'label' => 'Type de Vendeur',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4 col-md-6 col-12'
                ]
            ])

            // Champ de commentaire facultatif
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire (facultatif)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Ajoutez un commentaire (optionnel)',
                ],
                'row_attr' => [
                    'class' => 'col-12'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandesProspection::class,
            'attr' => [
                'class' => 'row gy-3 gx-3'
            ]
        ]);
    }
}