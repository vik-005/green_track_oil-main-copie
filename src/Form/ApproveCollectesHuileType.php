<?php

namespace App\Form;

use App\Entity\Vendeurs;
use App\Entity\TypesHuile;
use App\Entity\CollectesHuile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ApproveCollectesHuileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ 'volume' avec classes Bootstrap
       
            
            // Champ 'photoBidons' avec classes Bootstrap pour plusieurs fichiers
      
            
            // Champ 'prixAchat' avec classes Bootstrap
       
            
            // Champ 'vendeurs' avec classes Bootstrap
           
            
            // Champ 'typehuile' avec classes Bootstrap
          
            

            // Statut de la demande avec menu déroulant
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut de la demande',
                'choices' => [
                   
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
            ->add('commentaireMagasinier', TextareaType::class, [
                'label' => 'Commentaire du magasinier',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
            
            // Bouton de soumission avec classes Bootstrap
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CollectesHuile::class,
        ]);
    }
}
