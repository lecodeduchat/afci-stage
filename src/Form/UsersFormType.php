<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;



class UsersFormType extends AbstractType
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

            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'jj/mm/aaaa',
                ],
                'years' => range(date('Y') - 110, date('Y')),
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'La date de naissance doit correspondre au format "jj/mm/aaaa" (ex: 05/02/2000)',
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1 rue de la Paix'
                ],
                'label' => 'Adresse'
            ])
            ->add('zipcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '75000'
                ],
                'label' => 'Code postal',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Le code postal doit comporter 5 chiffres.'
                    ])
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Paris'
                ],
                'label' => 'Ville',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+[ \-\']?[[a-z]+[ \-\']?]*[a-z]+$/i',
                        'message' => 'La ville doit être valide.'
                    ])
                ],
            ])
            ->add('country', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'France',
                    'value' => 'France'
                ],
                'label' => 'Pays',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+[ \-\']?[[a-z]+[ \-\']?]*[a-z]+$/i',
                        'message' => 'Le pays doit être valide.'
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
