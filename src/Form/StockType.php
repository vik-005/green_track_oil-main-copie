<?php

// src/Form/StockType.php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\TypesHuile;
use App\Entity\TypeVendeur;
use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typehuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile', // Changez ceci en fonction de votre propriété de type huile
            ])
            ->add('quantiteInitiale', NumberType::class, [
                'label' => 'Quantité Initiale',
            ])
            
            ->add('quantiteStockee', NumberType::class, [
                'label' => 'Quantité Stockée',
            ])
                
            ->add('nomvendeur', EntityType::class, [
                'class' => TypeVendeur::class,
                'choice_label' => 'nomType', // Changez ceci en fonction de votre propriété de nom vendeur
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
