<?php

namespace App\Rules\TalentHeader;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class CheckStatus implements Rule
{
    public function passes($attribute, $value)
    {
        return in_array($value, ['Active', 'Inactive']);
    }

    public function message()
    {
        return 'The status must be either Active or Inactive.';
    }
}
