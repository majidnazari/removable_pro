<?php

namespace App\Rules\PersonMarriage;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage;

class UniqueMarriage implements Rule
{
    protected $manId;
    protected $womanId;
    protected $excludeMarriageId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $manId
     * @param  int  $womanId
     * @param  int|null  $excludeMarriageId
     */
    public function __construct($manId, $womanId, $excludeMarriageId = null)
    {
        $this->manId = $manId;
        $this->womanId = $womanId;
        $this->excludeMarriageId = $excludeMarriageId;
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
        // Query to check if a marriage already exists between the man and woman
        $query = PersonMarriage::where(function ($query) {
            $query->where('man_id', $this->manId)
                ->where('woman_id', $this->womanId);
        })->orWhere(function ($query) {
            $query->where('man_id', $this->womanId)
                ->where('woman_id', $this->manId);
        });

        // Exclude the current marriage record if provided
        if ($this->excludeMarriageId) {
            $query->where('id', '!=', $this->excludeMarriageId);
        }

        return !$query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A marriage between these two people already exists.';
    }
}
