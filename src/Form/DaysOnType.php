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
            ->add('start_morning')
            ->add('end_morning')
            ->add('start_afternoon')
            ->add('end_afternoon');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DaysOn::class,
        ]);
    }
}
