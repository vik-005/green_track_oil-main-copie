<?php

// src/Form/SortiesStockType.php

namespace App\Form;

use App\Entity\TypesHuile;
use App\Entity\SortiesStock;
use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SortiesStockType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Volume (string)
            ->add('volume', null, [
                'label' => 'Volume',
                'attr' => ['maxlength' => 255],
            ]);

            if ($this->security->isGranted('ROLE_ADMIN')){
                // Prix Vente (float)
        $builder->add('prixVente', NumberType::class, [
            'label' => 'Prix de Vente (€)',
            'scale' => 2,
        ]);
                
            }
     $builder    ->add('typehuile', EntityType::class, [
                'class' => TypesHuile::class,
                'choice_label' => 'nomTypeHuile', // Assuming 'nom' is the name field of the TypesHuile entity
                'label' => 'Type d\'huile',
                'attr' => ['class' => 'form-control'],
                'row_attr' => [
                    'class' => 'col-lg-4'
                ]
                ]);
           
        


        // Photo (file upload)
        $builder->add('photo', FileType::class, [
            'label' => 'Photo (JPEG, PNG)',
            'mapped' => false, // Indique que ce champ n'est pas mappé directement à l'entité
            'required' => false,
            'multiple'=>true,
            'constraints' => [
                
                new All([
                    'constraints'=>[
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG)',
                        ])
                    ]
                ])
            ],
        ])
            // Numéro Matériel (string)
            ->add('nummat', null, [
                'label' => 'Numéro Matériel',
                'attr' => ['maxlength' => 255],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortiesStock::class,
        ]);
    }
}