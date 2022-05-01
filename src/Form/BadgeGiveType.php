<?php

namespace App\Form;

use App\Entity\Achievement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BadgeGiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'placeholder' => 'Choisissez un utilisateur',
                'expanded' => true,
                'class' => User::class,
            ])
            ->add('achievement', EntityType::class, [
                'placeholder' => 'Choisissez un badge',
                'expanded' => true,
                'class' => Achievement::class,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ])
        ;
    }
}
