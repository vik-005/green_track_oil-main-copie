<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UtilisateursType extends AbstractType
{

    private $security;
    
    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'Utilisateur' => 'ROLE_USER',
            'Administrateur' => 'ROLE_ADMIN',
            'Agent de Collecte' => 'ROLE_AGENT',
            'Acheteur' => 'ROLE_ACHETEUR',
        ];

        if ($this->security->isGranted('ROLE_ROOT')){
            $roles['Super Administrateur'] = 'ROLE_SUPER_ADMIN';
        }

        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => $roles,
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
            ])
            ->add('nomUtilisateur', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('dateCreation', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('prenomutilisateur', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}