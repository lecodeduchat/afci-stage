<?php

namespace App\Form;

use App\Entity\DaysOn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DaysOnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date',
                ],
            ])
            ->add('start_morning', null, [
                'widget' => 'single_text',
                'label' => 'Début de matinée',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Start Morning',
                    'value' => '09:30',
                ],
            ])
            ->add('end_morning', null, [
                'widget' => 'single_text',
                'label' => 'Fin de matinée',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'End Morning',
                    'value' => '13:00',
                ],
            ])
            ->add('start_afternoon', null, [
                'widget' => 'single_text',
                'label' => 'Début d\'après-midi',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Start Afternoon',
                    'value' => '14:00',
                ],
            ])
            ->add('end_afternoon', null, [
                'widget' => 'single_text',
                'label' => 'Fin d\'après-midi',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'End Afternoon',
                    'value' => '18:00',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DaysOn::class,
        ]);
    }
}
