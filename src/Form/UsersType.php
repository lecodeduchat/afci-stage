<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname',TextType::class,['label'=>'Prénom'])
            ->add('lastname',TextType::class,['label'=>'Nom'])
            ->add('home_phone',TextType::class,['label'=>'Téléphone'])
            ->add('cell_phone',TextType::class,['label'=>'Téléphone Mobile'])
            ->add('is_blocked',null,['label'=>'bloqué'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
