<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UsersChildsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                        'pattern' => '/^[a-zéèë]+[ \-\']?[[a-zéèë]+[ \-\']?]*[a-zéèë]+$/i',
                        'message' => 'Le prénom doit être valide.'
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
