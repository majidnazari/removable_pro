<?php

namespace App\Rules\UserMergeRequest;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\Status;

class ReceiverHasOwner implements Rule
{
    public function passes($attribute, $value)
    {
        $person = Person::find($value);

        if (!$person) {
            return true; // Pass if person not found (to let other rules handle it)
        }

        $person_receiver_owner = Person::where('creator_id', $person->creator_id)
            ->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        return $person_receiver_owner !== null;
    }

    public function message()
    {
        return 'The receiver\'s owner is not found.';
    }
}
