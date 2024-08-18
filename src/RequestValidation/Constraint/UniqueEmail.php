<?php

namespace App\RequestValidation\Constraint;

use App\RequestValidation\Validator\UniqueEmailValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEmail extends Constraint
{
    public string $message = 'Email "{{ value }}" je već u upotrebi.';

    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}
