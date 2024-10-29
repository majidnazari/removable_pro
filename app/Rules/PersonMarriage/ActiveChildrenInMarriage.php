<?php

namespace App\Rules\PersonMarriage;

use App\Models\PersonChild;
use Illuminate\Contracts\Validation\Rule;

class ActiveChildrenInMarriage implements Rule
{
    protected $personMarriageId;

    public function __construct($personMarriageId)
    {
        $this->personMarriageId = $personMarriageId;
    }

    public function passes($attribute, $value)
    {
        // Check if the marriage has any active children associated with it
        return !PersonChild::where('person_marriage_id', $this->personMarriageId)->exists();
    }

    public function message()
    {
        return "Cannot delete this marriage because it has active child relationships.";
    }
}
