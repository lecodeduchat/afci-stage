<?php

namespace App\Form;

use App\Entity\Cares;
use App\Entity\Users;
use App\Entity\Appointments;
use App\Repository\CaresRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time')
            ->add('date')
            ->add('care', EntityType::class, [
                'class' => Cares::class,
                'choice_label' => 'name',
                'label' => 'Choix de la séance',
                'query_builder' => function (CaresRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
        // ->add('user', EntityType::class, [
        //     'class' => Users::class,
        //     'choice_label' => 'name',
        //     'label' => 'Choix du client',
        //     'query_builder' => function (UsersRepository $ur) {
        //         return $ur->createQueryBuilder('u')
        //             ->orderBy('u.name', 'ASC');
        //     },
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointments::class,
        ]);
    }
}
