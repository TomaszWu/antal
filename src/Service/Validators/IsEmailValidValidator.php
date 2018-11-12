<?php

namespace App\Service\Validators;

class IsEmailValidValidator
{
    const _FILER_ = FILTER_VALIDATE_EMAIL;

    public function validate(string $email): bool
    {
        return filter_var($email, self::_FILER_);
    }
}
