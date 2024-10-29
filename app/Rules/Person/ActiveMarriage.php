<?php

namespace App\Rules\Person;

use App\Models\PersonMarriage;
use Illuminate\Contracts\Validation\Rule;

class ActiveMarriage implements Rule
{
    protected $personId;

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    public function passes($attribute, $value)
    {
        // Check if the person is part of any marriage
        return !PersonMarriage::where('man_id', $this->personId)
            ->orWhere('woman_id', $this->personId)
            ->exists();
    }

    public function message()
    {
        return "Cannot delete this person because they are part of an active marriage.";
    }
}
