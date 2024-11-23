<?php

namespace App\Form;

use App\Entity\StockHuile;
use App\Entity\TypesHuile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockHuileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('dateMaj')
            ->add('typeHuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile', // Assuming TypesHuile has a 'nom' field
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockHuile::class,
        ]);
    }
}
