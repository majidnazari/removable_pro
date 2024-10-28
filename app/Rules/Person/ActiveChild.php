<?php

namespace App\Rules\Person;

use App\Models\PersonChild;
use Illuminate\Contracts\Validation\Rule;

class ActiveChild implements Rule
{
    protected $personId;

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    public function passes($attribute, $value)
    {
        // Check if the person has any active children
        return !PersonChild::where('child_id', $this->personId)->exists();
    }

    public function message()
    {
        return "Cannot delete this person because they have active child relationships.";
    }
}
