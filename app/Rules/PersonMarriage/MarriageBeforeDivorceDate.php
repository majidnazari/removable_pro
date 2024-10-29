<?php

namespace App\Rules\PersonMarriage;


use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class MarriageBeforeDivorceDate implements Rule
{
    protected $marriageDate;
    protected $divorceDate;

    /**
     * Create a new rule instance.
     *
     * @param  string|null  $marriageDate
     * @param  string|null  $divorceDate
     */
    public function __construct($marriageDate, $divorceDate)
    {
        $this->marriageDate = $marriageDate;
        $this->divorceDate = $divorceDate;
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
        // If either date is missing, skip the check
        if (!$this->marriageDate || !$this->divorceDate) {
            return true;
        }

        // Parse the dates
        $marriageDate = Carbon::parse($this->marriageDate);
        $divorceDate = Carbon::parse($this->divorceDate);

        // Return true only if marriageDate is before divorceDate
        return $marriageDate->lessThan($divorceDate);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The marriage date must be before the divorce date.';
    }
}