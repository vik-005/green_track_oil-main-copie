<?php

namespace App\Form;

use App\Entity\TypeVendeur;
use App\Entity\Utilisateurs;
use App\Entity\DemandesProspection;
use Symfony\Component\Form\AbstractType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandesProspectionAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          

            
           



            // Statut de la demande avec menu déroulant
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut de la demande',
                'choices' => [
                    'En attente' => 'en_attente',
                    'Approuvé' => 'approuve',
                    'Rejeté' => 'rejete',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => [
                    'class' => 'col-lg-4'
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
                    'class' => 'col-lg-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandesProspection::class,
        ]);
    }
}
