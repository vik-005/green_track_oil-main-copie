<?php

namespace App\Form;

use App\Entity\Vendeurs;
use App\Entity\TypesHuile;
use App\Entity\TypeVendeur;
use App\Entity\CollectesHuile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CollectesHuileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Volume collecté
            ->add('volume', NumberType::class, [
                'label' => 'Volume collecté (litres)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le volume collecté',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ])

            // Prix d'achat
            ->add('prixAchat', NumberType::class, [
                'label' => 'Prix d\'achat (CFA)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le prix d\'achat',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ])

            // Photos des bidons
            ->add('photoBidons', FileType::class, [
                'label' => 'Photos des bidons',
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control-file',
                    'accept' => 'image/*',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ])

            // Commentaire du supérieur
            ->add('commentaireSuperieur', TextareaType::class, [
                'label' => 'Commentaire du supérieur',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Ajoutez un commentaire ici...',
                ],
                'row_attr' => [
                    'class' => 'col-12 mb-3',
                ],
            ])

            // Sélection du vendeur
            ->add('vendeurs', EntityType::class, [
                'class' => Vendeurs::class,
                'choice_label' => 'nomVendeur',
                'label' => 'Vendeur',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ])

            // Type d'huile
            ->add('typehuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile',
                'label' => 'Type d\'huile',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ])

            // Type de vendeur
            ->add('typevendeur', EntityType::class, [
                'class' => TypeVendeur::class,
                'choice_label' => 'nomType',
                'label' => 'Type de vendeur',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-6 col-lg-4 mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CollectesHuile::class,
            'attr' => [
                'class' => 'row g-3', // Classes Bootstrap pour des espacements fluides
            ],
        ]);
    }
}