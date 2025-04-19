<?php

namespace App\Rules\PersonMarriage;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage;

class OneActiveMarriage implements Rule
{
    protected $personId;
    protected $excludeMarriageId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $personId
     * @param  int|null  $excludeMarriageId
     */
    public function __construct($personId, $excludeMarriageId = null)
    {
        $this->personId = $personId;
        $this->excludeMarriageId = $excludeMarriageId;
    }

    public function passes($attribute, $value)
    {
        // Query for active marriages for the person, excluding the current marriage if $excludeMarriageId is provided
        $query = PersonMarriage::where(function ($query) {
            $query->where('man_id', $this->personId)
                ->orWhere('woman_id', $this->personId);
        })->where('status', 'Active');

        if ($this->excludeMarriageId) {
            $query->where('id', '!=', $this->excludeMarriageId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return 'A person can have only one active marriage.';
    }
}
