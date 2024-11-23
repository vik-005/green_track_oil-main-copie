<?php

namespace App\Form;

use App\Entity\Vendeurs;
use App\Entity\EntreStock;
use App\Entity\TypesHuile;
use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EntreStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le vendeur
            ->add('vendeur', EntityType::class, [
                'class' => Vendeurs::class,
                'choice_label' => 'nomvendeur', // Assurez-vous que l'entité Vendeurs a une propriété 'nom'
                'label' => 'Vendeur',
                'placeholder' => 'Sélectionnez un vendeur',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
            ])
            
            // Champ pour le nombre de bidons
            ->add('nombrebidons', NumberType::class, [
                'label' => 'Nombre de Bidons',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nombre de bidons',
                    'min' => 0,
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
            ])
            
            // Champ pour le prix unitaire
            ->add('prixunitaire', NumberType::class, [
                'label' => 'Prix Unitaire (€)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le prix unitaire',
                    'step' => '0.01',
                    'min' => 0,
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
            ])
            
            // Champ pour le total
            ->add('total', NumberType::class, [
                'label' => 'Total (€)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le total',
                    'step' => '0.01',
                    'min' => 0,
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                'mapped' => false, // Si vous souhaitez calculer le total automatiquement
                'required' => false,
            ])
            
            // Champ pour la date d'enregistrement
            ->add('dateEnregisterement', DateTimeType::class, [
                'label' => 'Date d\'Enregistrement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                //'data' => new \DateTime(), // Définit la date actuelle par défaut
               
                'required' => false,
            ])
            
            // Champ pour l'agent
            ->add('agent', EntityType::class, [
                'class' => Utilisateurs::class,
                'query_builder' => function(UtilisateurRepository $utilisateurRepo) {
                    return $utilisateurRepo->createQueryBuilder('u')
                        ->andWhere("u.role LIKE '%ROLE_AGENT%'");
                },
                'choice_label' => 'nomUtilisateur', // Assurez-vous que l'entité Utilisateurs a une propriété 'nomUtilisateur'
                'label' => 'Agent Responsable',
                'placeholder' => 'Sélectionnez un agent',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                'required' => true,
            ])
            
            // Champ pour le type d'huile
            ->add('typehuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomtypehuile', // Assurez-vous que l'entité TypesHuile a une propriété 'nom'
                'label' => 'Type d\'Huile',
                'placeholder' => 'Sélectionnez un type d\'huile',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                'required' => true,
            ])
            
            // Champ pour le magasinier
            ->add('nommagasinier', EntityType::class, [
                'class' => Utilisateurs::class,
                'query_builder' => function(UtilisateurRepository $utilisateurRepo) {
                    return $utilisateurRepo->createQueryBuilder('u')
                        ->andWhere("u.role LIKE '%ROLE_MAGASINIER%'");
                },
                'choice_label' => 'nomUtilisateur', // Assurez-vous que l'entité Utilisateurs a une propriété 'nomUtilisateur'
                'label' => 'Magasinier Responsable',
                'placeholder' => 'Sélectionnez un magasinier',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntreStock::class,
        ]);
    }
}