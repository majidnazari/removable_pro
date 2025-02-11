<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidDivorceDate implements Rule
{
    protected $marriageDate;
    protected $fatherBirthDate;
    protected $motherBirthDate;

    public function __construct($marriageDate, $fatherBirthDate, $motherBirthDate)
    {
        $this->marriageDate = $marriageDate;
        $this->fatherBirthDate = $fatherBirthDate;
        $this->motherBirthDate = $motherBirthDate;
    }

    public function passes($attribute, $value)
    {
        $divorceDate = Carbon::parse($value);

        if (!$this->marriageDate || !$this->fatherBirthDate || !$this->motherBirthDate) {
            return false; // Ensure all required dates are provided
        }

        return $divorceDate->greaterThan(Carbon::parse($this->marriageDate)) &&
               $divorceDate->greaterThan(Carbon::parse($this->fatherBirthDate)) &&
               $divorceDate->greaterThan(Carbon::parse($this->motherBirthDate));
    }

    public function message()
    {
        return "Divorce date must be after marriage and both parents' birth dates.";
    }
}
