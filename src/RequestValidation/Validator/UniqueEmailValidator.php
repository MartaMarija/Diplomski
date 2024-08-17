<?php

namespace App\RequestValidation\Validator;

use Pimcore\Model\DataObject\Member;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $existingMember = Member::getByEmail($value, 1);

        if ($existingMember) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

