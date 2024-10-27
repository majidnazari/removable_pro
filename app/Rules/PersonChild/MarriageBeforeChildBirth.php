<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;
use Carbon\Carbon;

class MarriageBeforeChildBirth implements Rule
{
    protected $marriageId;

    /**
     * Create a new rule instance.
     *
     * @param int $marriageId
     * @return void
     */
    public function __construct($marriageId)
    {
        $this->marriageId = $marriageId;
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
        $child = Person::find($value);
        $marriage = PersonMarriage::find($this->marriageId);

        // Check if both marriage and child data are available
        if ($marriage && $child) {
            $marriageDate = Carbon::parse($marriage->marriage_date);
            $childBirthDate = Carbon::parse($child->birth_date);

            // Ensure the marriage date is at least 9 months before the child’s birth date
            return $marriageDate->diffInMonths($childBirthDate) >= 9;
        }

        // If either marriage or child data is missing, validation fails
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The marriage date must be at least 9 months before the child’s birth date.';
    }
}
