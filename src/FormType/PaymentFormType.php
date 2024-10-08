<?php

namespace App\FormType;

use App\RequestValidation\Constraint\PaymentFileRequired;
use Pimcore\Model\DataObject\ClassDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $paymentClass = ClassDefinition::getByName('Payment');
        $paymentTypes = $paymentClass->getFieldDefinition('PaymentType');
        $choices = [];
        foreach ($paymentTypes->getOptions() as $option) {
            $choices[$option['key']] = $option['value'];
        }

        $builder
            ->add('PaymentType', ChoiceType::class, [
                'label' => 'NAČIN PLAĆANJA',
                'placeholder' => '-- Odaberite način plaćanja --',
                'required' => true,
                'choices' => $choices
            ])
            ->add('File', FileType::class, [
                'label' => 'POTVRDA O UPLATI',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'application/pdf',
                    'placeholder' => 'Učitajte potvrdu o uplati'
                ],
                'constraints' => [
                    new PaymentFileRequired(),
                ],
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'POTVRDI',
            ])
        ;
    }
}
