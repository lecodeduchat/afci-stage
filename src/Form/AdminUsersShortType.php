<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUsersShortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'email@example.com'
                ],
                'label' => 'E-mail',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$/',
                        'message' => 'L\'adresse e-mail doit être valide.'
                    ])
                ],
            ])
            // Ajout manuel des autres champs de la table users
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+[ \-\']?[[a-z]+[ \-\']?]*[a-z]+$/i',
                        'message' => 'Le nom doit être valide.'
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'
                ],
                'label' => 'Prénom',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+[ \-\']?[[a-z]+[ \-\']?]*[a-z]+$/i',
                        'message' => 'Le prénom doit être valide.'
                    ])
                ],
            ])
            ->add('home_phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '03.20.77.88.99'
                ],
                'label' => 'Téléphone fixe',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[1-68]([.]?[0-9]{2}){4}$/',
                        'message' => 'Le numéro de téléphone doit être valide.'
                    ])
                ],

            ])
            ->add('cell_phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '06.66.77.88.99'
                ],
                'label' => 'Téléphone mobile',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[6-7]([.]?[0-9]{2}){4}$/',
                        'message' => 'Le numéro de téléphone doit être valide.'
                    ])
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
