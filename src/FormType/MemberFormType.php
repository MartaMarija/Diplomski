<?php

namespace App\FormType;

use App\Model\Member;
use App\RequestValidation\Constraint\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class MemberFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $myProfile = $options['myProfile'];

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
        ;

        if (!$myProfile) {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'EMAIL',
                    'required' => true,
                    'constraints' => [
                        new UniqueEmail(),
                    ],
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'myProfile' => false
        ]);
    }
}
