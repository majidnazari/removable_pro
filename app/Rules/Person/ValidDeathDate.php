<?php

namespace App\Rules\Person;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidDeathDate implements Rule
{
    protected $birthDate;
    protected $marriageDate;
    protected $divorceDate;

    public function __construct($birthDate, $marriageDate = null, $divorceDate = null)
    {
        $this->birthDate = $birthDate ? Carbon::parse($birthDate) : null;
        $this->marriageDate = $marriageDate ? Carbon::parse($marriageDate) : null;
        $this->divorceDate = $divorceDate ? Carbon::parse($divorceDate) : null;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) {
            return true; // If no death_date is provided, validation should pass
        }

        $deathDate = Carbon::parse($value);

        // 1. Death date must be after or equal to birth date
        if ($this->birthDate && $deathDate->lt($this->birthDate)) {
            return false;
        }

        // 2. If marriage date exists, death date must be after or equal to it
        if ($this->marriageDate && $deathDate->lt($this->marriageDate)) {
            return false;
        }

        // 3. If divorce date exists, death date must be after or equal to it
        if ($this->divorceDate && $deathDate->lt($this->divorceDate)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The death date must follow the sequence: birth_date ≤ marriage_date ≤ divorce_date ≤ death_date.";
    }
}
