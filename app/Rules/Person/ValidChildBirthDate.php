<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidChildBirthDate implements Rule
{
    public function passes($attribute, $value)
    {
        $childBirthDate = Carbon::parse($value);
        $marriageDate = request()->input('marriage_date');
        $divorceDate = request()->input('divorce_date');

        $minBirthAfterMarriage = Carbon::parse($marriageDate)->addMonths(9);
        
        if ($divorceDate) {
            return $childBirthDate->greaterThanOrEqualTo($minBirthAfterMarriage) &&
                   $childBirthDate->lessThanOrEqualTo(Carbon::parse($divorceDate));
        }

        return $childBirthDate->greaterThanOrEqualTo($minBirthAfterMarriage);
    }

    public function message()
    {
        return "Child's birth date must be at least 9 months after marriage and before divorce date.";
    }
}
