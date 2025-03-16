<?php

namespace App\Rules\UserMergeRequest;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;


class PersonIsAccessibleBySender implements Rule
{
    protected $senderId;

    public function __construct($senderId)
    {
        $this->senderId = $senderId;
    }

    public function passes($attribute, $value)
    {
        $person = Person::find($value);
        return $person && $person->creator_id === $this->senderId;
    }

    public function message()
    {
        return "You don't have access to another family.";
    }
}
