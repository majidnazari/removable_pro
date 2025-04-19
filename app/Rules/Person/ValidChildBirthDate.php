<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidChildBirthDate implements Rule
{

    protected $marriageDate;
    protected $divorceDate;


    public function __construct($marriageDate, $divorceDate)
    {
        $this->marriageDate = $marriageDate;
        $this->divorceDate = $divorceDate;

    }

    public function passes($attribute, $value)
    {
        $childBirthDate = Carbon::parse($value);
        // $marriageDate = request()->input('marriage_date');
        //$divorceDate = request()->input('divorce_date');

        $minBirthAfterMarriage = Carbon::parse($this->marriageDate)->addMonths(6);

        if ($this->divorceDate) {
            return $childBirthDate->greaterThanOrEqualTo($minBirthAfterMarriage) &&
                $childBirthDate->lessThanOrEqualTo(Carbon::parse($this->divorceDate));
        }

        return $childBirthDate->greaterThanOrEqualTo($minBirthAfterMarriage);
    }

    public function message()
    {
        return "Child's birth date must be at least 6 months after marriage and before divorce date.";
    }
}
