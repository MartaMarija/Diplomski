<?php

namespace App\RequestValidation\Constraint;

use App\RequestValidation\Validator\PaymentFileRequiredValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PaymentFileRequired extends Constraint
{
    public string $message = 'Uplata je obvezna kod plaćanja Online.';

    public function validatedBy(): string
    {
        return PaymentFileRequiredValidator::class;
    }
}
