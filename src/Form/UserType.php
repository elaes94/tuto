<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            // ->add('roles')
            ->add('firstname', null, ['label' => 'Prénom'])
            ->add('lastname', null, ['label' => 'Nom'])
            ->add('phone_1', null, ['label' => 'Numéro de téléphone n°1'])
            ->add('phone_2', null, ['label' => 'Numéro de téléphone n°2'])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices'  => [
                    'membre' => "membre",
                    'professionnel' => "professionnel", 
                ],
            ])
            // ->add('isVerified')
            ->add('sauvegarder', SubmitType::class, [                
                'validate' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
