<?php

namespace App\RequestValidation\Validator;

use Pimcore\Model\DataObject\Member;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentFileRequiredValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var FormInterface $form */
        $form = $this->context->getObject();

        $paymentType = $form->getParent()->getData()->getPaymentType();

        if ($paymentType === 'Online' && empty($value)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('File')
                ->addViolation();
        }
    }
}

