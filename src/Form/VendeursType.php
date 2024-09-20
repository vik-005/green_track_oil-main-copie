<?php

namespace App\Form;

use App\Entity\Vendeurs;
use App\Entity\TypeVendeur;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class VendeursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomvendeur', TextType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('adresse', TextType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('ville', TextType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('pays', ChoiceType::class, [
                'choices' =>  array_flip(Countries::getNames()),
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('telephone', TelType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('dateCreation', DateType::class, [
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('typevendeur', EntityType::class, [
                'class' => TypeVendeur::class,
                'choice_label' => 'nomType',
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'mt-2 btn btn-primary'],
                'row_attr' => [
                    'class' => 'col-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vendeurs::class,
            'attr' => [
                'class' => 'row gy-2'
            ]
        ]);
    }
}
