<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'email@example.com'
                ],
                'label' => 'E-mail'
            ])
            // Ajout manuel des autres champs de la table users
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'
                ],
                'label' => 'Prénom'
            ])
            ->add('home_phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '06.66.77.88.99'
                ],
                'label' => 'Téléphone fixe',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[1-9]([-. ]?[0-9]{2}){4}$/',
                        'message' => 'Le numéro de téléphone doit comporter 10 chiffres.'
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
                        'pattern' => '/^0[1-9]([-. ]?[0-9]{2}){4}$/',
                        'message' => 'Le numéro de téléphone doit comporter 10 chiffres.'
                    ])
                ],
            ])
            ->add('birthday', DateType::class, [

                'label' => 'Date de naissance',
                'widget' => 'single_text',
                // Pour utiliser datepicker il faut désactiver html5
                'html5' => false,
                // Ajout de l'attribut 'datepicker' pour le JS
                'attr' => [
                    'class' => 'form-control',
                    // 'class' => 'js-datepicker',
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
                'label' => 'Code postal'
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Paris'
                ],
                'label' => 'Ville'
            ])
            ->add('country', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'France',
                    'value' => 'France'
                ],
                'label' => 'Pays'
            ])
            // Modifier 'agreeTerms' par 'RGPDConsent'
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'En m\'inscrivant à ce site j\'accepte... '
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au minimun {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
