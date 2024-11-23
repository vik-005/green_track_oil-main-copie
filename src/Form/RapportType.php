<?php

namespace App\Form;

use App\Entity\Rapport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RapportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nomrestaurant', TextType::class, [
                'label' => 'Nom du restaurant',
                'attr' => ['class' => 'form-control'],
            ])
            
            ->add('numresto', TextType::class, [
                'label' => 'Numéro du restaurant',
                'attr' => ['class' => 'form-control'],
            ])

            ->add('rencontre', TextType::class, [
                'label' => 'Rencontre',
                'attr' => ['class' => 'form-control'],
            ])
            
            ->add('Adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
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
                    'class' => 'mb-3',
                ],
            ])
          
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapport::class,
        ]);
    }
}