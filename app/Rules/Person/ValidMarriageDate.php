<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Log;

class ValidMarriageDate implements Rule
{
    protected $fatherBirthDate;
    protected $motherBirthDate;
    protected $errorMessage;

    public function __construct($fatherBirthDate, $motherBirthDate)
    {
        $this->fatherBirthDate = $fatherBirthDate;
        $this->motherBirthDate = $motherBirthDate;
        $this->errorMessage = "Invalid marriage date.";
    }

    public function passes($attribute, $value)
    {
        if (!$this->fatherBirthDate || !$this->motherBirthDate || !$value) {
            $this->errorMessage = "Marriage date, father's birth date, and mother's birth date are required.";
            return false;
        }

        $marriageDate = Carbon::parse($value);
        $fatherBirthDate = Carbon::parse($this->fatherBirthDate);
        $motherBirthDate = Carbon::parse($this->motherBirthDate);

        // Check if marriage date is after both parents' birth dates
        if (!$marriageDate->greaterThan($fatherBirthDate)) {
            $this->errorMessage = "Marriage date must be after the father's birth date.";
            return false;
        }

        if (!$marriageDate->greaterThan($motherBirthDate)) {
            $this->errorMessage = "Marriage date must be after the mother's birth date.";
            return false;
        }

        // Ensure the father is at least 15 years old at marriage
        if ($fatherBirthDate->diffInYears($marriageDate) < 12) {
            $this->errorMessage = "Father must be at least 12 years old at the time of marriage.";
            return false;
        }

        // Ensure the mother is at least 9 years old at marriage
        if ($motherBirthDate->diffInYears($marriageDate) < 9) {
            $this->errorMessage = "Mother must be at least 9 years old at the time of marriage.";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
