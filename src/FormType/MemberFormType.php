<?php

namespace App\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class MemberFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, [
                'label' => 'IME',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ime...',
                    'class' => 'input',
                ],
            ])
            ->add('LastName', TextType::class, [
                'label' => 'PREZIME',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Prezime...',
                    'class' => 'input',
                ],
            ])
            ->add('OIB', TextType::class, [
                'label' => 'OIB',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 11,
                        'max' => 11,
                        'exactMessage' => 'This field must be exactly 11 characters long.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'OIB...',
                    'class' => 'input',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'EMAIL',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mail@example.com',
                    'class' => 'input',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'LOZINKA',
                    'attr' => [
                        'placeholder' => 'Lozinka...',
                        'class' => 'input',
                        'autocomplete' => 'off',
                    ],
                ],
                'second_options' => [
                    'label' => 'PONOVI LOZINKU',
                    'attr' => [
                        'placeholder' => 'Lozinka...',
                        'class' => 'input',
                        'autocomplete' => 'off',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
            ]);
    }
}
