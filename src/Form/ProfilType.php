<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, ['label' => 'Prénom', 'empty_data' => ''])
            ->add('lastname', null, ['label' => 'Nom', 'empty_data' => ''])
            ->add('phone_1', null, ['label' => 'Numéro de téléphone n°1', 'empty_data' => ''])
            ->add('phone_2', null, ['label' => 'Numéro de téléphone n°2', 'empty_data' => ''])
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
