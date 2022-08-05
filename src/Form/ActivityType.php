<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Region;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de l\'activité', 'empty_data' => ''])
            ->add('description', null, ['label' => 'Description', 'empty_data' => ''])
            ->add('website', null, ['label' => 'Lien du site ou réseaux sociaux', 'empty_data' => ''])
            ->add('localisation', null, ['label' => 'Emplacement', 'empty_data' => ''])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
            ->add('sauvegarder', SubmitType::class, [                
                'validate' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
