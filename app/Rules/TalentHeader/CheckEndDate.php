<?php

namespace App\Rules\TalentHeader;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class CheckEndDate implements Rule
{
    public function passes($attribute, $value)
    {
        return Carbon::parse($value)->lte(Carbon::today());
    }

    public function message()
    {
        return 'The end date cannot be later than today.';
    }
}
