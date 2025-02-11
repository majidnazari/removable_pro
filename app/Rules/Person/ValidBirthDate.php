<?php

namespace App\Rules\Person;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidBirthDate implements Rule
{
    public function __construct()
    {
        // Constructor logic (if needed)
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
        $birthDate = Carbon::parse($value);

        // If the father, validate that the birth date is at least 15 years ago
        if (strpos($attribute, 'father') !== false) {
            return $birthDate->lte(Carbon::now()->subYears(15)); // Father's birth date should be at least 15 years ago
        }

        // If the mother, validate that the birth date is at least 9 years ago
        if (strpos($attribute, 'mother') !== false) {
            return $birthDate->lte(Carbon::now()->subYears(9)); // Mother's birth date should be at least 9 years ago
        }

        // Default validation
        return $birthDate->lte(Carbon::now()); // Ensures birth date is not in the future
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The birth date is not valid. Father must be at least 15 years old, and mother must be at least 9 years old.";
    }
}
