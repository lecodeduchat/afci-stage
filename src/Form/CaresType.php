<?php

namespace App\Form;

use App\Entity\Cares;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['attr' => ['class' => 'name_cares'],'label' =>'Nom:'])
            ->add('price',TextType::class,['attr' => ['class' => 'price_cares'],'label' =>'Prix:'])
            ->add('duration',null,['attr' => ['class' => 'duration_cares'],'label' =>'durée:'])
            ->add('color', ColorType::class,['attr' => ['class' => 'color_cares'],'label' =>'Couleur:'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cares::class,
        ]);
    }
}
