<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage;

class ValidMarriage implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the marriage exists
        return PersonMarriage::find($value) !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The specified marriage does not exist.';
    }
}
