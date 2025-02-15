<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Log;

class ValidMarriageDate implements Rule
{
    protected $manBirthDate;
    protected $womanBirthDate;
    protected $errorMessage;

    public function __construct($manBirthDate, $womanBirthDate)
    {
        $this->manBirthDate = $manBirthDate;
        $this->womanBirthDate = $womanBirthDate;
        $this->errorMessage = "Invalid marriage date.";
    }

    public function passes($attribute, $value)
    {
        if (!$this->manBirthDate || !$this->womanBirthDate || !$value) {
            $this->errorMessage = "Marriage date, man's birth date, and woman's birth date are required.";
            return false;
        }

        $marriageDate = Carbon::parse($value);
        $manBirthDate = Carbon::parse($this->manBirthDate);
        $womanBirthDate = Carbon::parse($this->womanBirthDate);

        // Check if marriage date is after both parents' birth dates
        if (!$marriageDate->greaterThan($manBirthDate)) {
            $this->errorMessage = "Marriage date must be after the man's birth date.";
            return false;
        }

        if (!$marriageDate->greaterThan($womanBirthDate)) {
            $this->errorMessage = "Marriage date must be after the woman's birth date.";
            return false;
        }

        // Ensure the Man is at least 15 years old at marriage
        if ($manBirthDate->diffInYears($marriageDate) < 12) {
            $this->errorMessage = "Man must be at least 12 years old at the time of marriage.";
            return false;
        }

        // Ensure the Woman is at least 9 years old at marriage
        if ($womanBirthDate->diffInYears($marriageDate) < 9) {
            $this->errorMessage = "Woman must be at least 9 years old at the time of marriage.";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
