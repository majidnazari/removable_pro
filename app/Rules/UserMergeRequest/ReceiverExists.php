<?php

namespace App\Rules\UserMergeRequest;

use App\Models\User;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\Status;

class ReceiverExists implements Rule
{
    public function passes($attribute, $value)
    {
        $person = Person::find($value);

        if (!$person) {
            return false; // Fail if person does not exist
        }

        $user_receiver = User::where('mobile', $person->country_code . $person->mobile)
            ->where('status', Status::Active)
            ->first();

        return $user_receiver !== null;
    }

    public function message()
    {
        return 'The node you have selected is not associated with an active user.';
    }
}
