<?php

namespace App\Rules\UserMergeRequest;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class NodeSenderNotOwner implements Rule
{
    public function passes($attribute, $value)
    {
        $person = Person::find($value);
        return !$person || !$person->is_owner; // Pass if not found or not owner
    }

    public function message()
    {
        return 'The owner cannot send requests to others.';
    }
}
