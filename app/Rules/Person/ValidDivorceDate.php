<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidDivorceDate implements Rule
{
    protected $marriageDate;
    protected $manBirthDate;
    protected $womanBirthDate;

    public function __construct($marriageDate, $manBirthDate, $womanBirthDate)
    {
        $this->marriageDate = $marriageDate;
        $this->manBirthDate = $manBirthDate;
        $this->womanBirthDate = $womanBirthDate;
    }

    public function passes($attribute, $value)
    {
        $divorceDate = Carbon::parse($value);

        if (!$this->marriageDate || !$this->manBirthDate || !$this->womanBirthDate) {
            return false; // Ensure all required dates are provided
        }

        return $divorceDate->greaterThan(Carbon::parse($this->marriageDate)) &&
               $divorceDate->greaterThan(Carbon::parse($this->manBirthDate)) &&
               $divorceDate->greaterThan(Carbon::parse($this->womanBirthDate));
    }

    public function message()
    {
        return "Divorce date must be after marriage and both parents' birth dates.";
    }
}
