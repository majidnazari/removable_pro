<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonChild;
use App\Models\PersonMarriage;

class UniquePersonChild implements Rule
{
    protected $personMarriageId;
    protected $childId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $personMarriageId
     * @param  int  $childId
     * @return void
     */
    public function __construct($personMarriageId, $childId)
    {
        $this->personMarriageId = $personMarriageId;
        $this->childId = $childId;
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
        // Check if a PersonChild relationship with the same marriage and child IDs already exists
        return !PersonChild::where('child_id', $this->childId)
            //->where('person_marriage_id', $this->personMarriageId)            
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // Fetch the existing marriage for this child, if any
        $existingMarriage = PersonChild::where('child_id', $this->childId)->first();

        // If no existing marriage is found, return a generic message
        if (!$existingMarriage) {
            return "This child is already associated with another marriage.";
        }

        // Retrieve the parent names from the existing marriage
        $marriage = PersonMarriage::with(['Man', 'Woman'])->find($existingMarriage->person_marriage_id);

        // Construct the parent names
        $parentNames = [];
        if ($marriage && $marriage->Man) {
            $parentNames[] = $marriage->Man->first_name . ' ' . $marriage->Man->last_name;
        }
        if ($marriage && $marriage->Woman) {
            $parentNames[] = $marriage->Woman->first_name . ' ' . $marriage->Woman->last_name;
        }

        // Join parent names for the error message
        $parentNamesStr = implode(' and ', $parentNames);

        return "This child is already associated with another marriage to {$parentNamesStr}.";
    }
}
