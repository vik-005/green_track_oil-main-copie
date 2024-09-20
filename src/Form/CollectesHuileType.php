<?php

namespace App\Form;

use App\Entity\Vendeurs;
use App\Entity\TypesHuile;
use App\Entity\Utilisateurs;
use App\Entity\CollectesHuile;
use Symfony\Component\Form\AbstractType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CollectesHuileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajout du champ 'volume' avec un type NumberType
            ->add('volume', NumberType::class, [
                'label' => 'Volume (en litres)',
                
                'attr' => ['placeholder' => 'Entrez le volume d\'huile collecté'],
                
                'required' => true,
            ])
            
            // Ajout du champ 'photoBidons' avec un type FileType
            ->add('photoBidons', FileType::class, [
                'label' => 'Photo des bidons',
                'mapped' => false, // Indique que ce champ n'est pas mappé à l'entité
                'required' => false,
            ])
            
            // Ajout du champ 'prixAchat' avec un type NumberType
            ->add('prixAchat', NumberType::class, [
                'label' => 'Prix d\'achat (par litre)',
                'attr' => ['placeholder' => 'Entrez le prix d\'achat'],
                'required' => true,
            ])
            
            // Ajout du champ 'dateCollecte' avec un type DateType
            ->add('dateCollecte', DateType::class, [
                'label' => 'Date de collecte',
                'widget' => 'single_text', // Utilise un champ de type date unique
                'required' => true,
            ])
            
            // Ajout du champ 'vendeurs' avec un type EntityType pour sélectionner un vendeur
            ->add('vendeurs', EntityType::class, [
                'class' => Vendeurs::class,
                'choice_label' => 'nomVendeur', // Affiche le nom du vendeur
                'label' => 'Vendeur',
                'placeholder' => 'Sélectionnez un vendeur',
                'required' => true,
            ])
            
            // Ajout du champ 'utilisateurs' avec un type EntityType pour sélectionner un utilisateur
            ->add('utilisateurs', EntityType::class, [
                'class' => Utilisateurs::class,
                'query_builder' => function(UtilisateurRepository $utilisateurRepo){
                    return $utilisateurRepo->createQueryBuilder('u')
                        ->andWhere("u.role LIKE '%ROLE_SUPER_ADMIN%'");
                },
                'choice_label' => 'nomUtilisateur', // Affiche le nom de l'utilisateur
                'label' => 'Agent de collecte',
                'attr' => [
                    'class' => 'js-select2 form-control',
                    'data-search' => 'on'
                ],
                'placeholder' => 'Sélectionnez un utilisateur',
                'required' => true,
            ])
            
            // Ajout du champ 'typehuile' avec un type EntityType pour sélectionner le type d'huile
            ->add('typehuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile', // Affiche le nom du type d'huile
                'label' => 'Type d\'huile',
                'placeholder' => 'Sélectionnez un type d\'huile',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CollectesHuile::class,
            'attr' => [
                'class' => 'row gy-2'
            ]
        ]);
    }
}
