<?php

namespace App\Form;

use App\Entity\DaysOff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class DaysOffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', null, [
                'widget' => 'single_text',
                'label' => 'Début',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Début',
                ],
            ])
            ->add('end', null, [
                'widget' => 'single_text',
                'label' => 'Fin',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fin',
                ],
            ])
            ->add('title')
            ->add('color', ColorType::class, ['attr' => ['class' => 'color_cares'], 'label' => 'Couleur : '])
            ->add('all_day');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DaysOff::class,
        ]);
    }
}
