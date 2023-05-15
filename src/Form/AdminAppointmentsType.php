<?php

namespace App\Form;

use App\Entity\Cares;
use App\Entity\Users;
use App\Entity\Childs;
use App\Entity\Appointments;
use App\Repository\CaresRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AdminAppointmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'attr' => [
                    'class' => 'form_appointment',

                ],
                'label' => 'Date et heure de la séance',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])
            ->add('care', EntityType::class, [
                'attr' => ['class' => 'form_appointment'],
                'class' => Cares::class,
                'choice_label' => 'name',
                'label' => 'Choix de la séance',
                'query_builder' => function (CaresRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('user_id', null, ['attr' => ['class' => 'form_appointment']])
            ->add('child_id', null, ['attr' => ['class' => 'form_appointment']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointments::class,
        ]);
    }
}
