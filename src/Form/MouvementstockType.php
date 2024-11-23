<?php

namespace App\Form;

use App\Entity\TypesHuile;
use App\Entity\MouvementStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MouvementstockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeHuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile', // Assuming TypesHuile has a 'nom' field
            ])
            ->add('quantite', NumberType::class)
            ->add('typeMouvement', ChoiceType::class, [
                'choices'  => [
                    'Entrée' => 'entree',
                    'Sortie' => 'sortie',
                ],
            ])
            ->add('dateMouvement', DateTimeType::class, [
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MouvementStock::class,
        ]);
    }
}
