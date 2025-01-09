<?php

namespace App\Rules\Person;

use App\Models\Person;
use App\GraphQL\Enums\Status;
use Illuminate\Contracts\Validation\Rule;

class ActivePersonRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Person::where('id', $value)
            ->where('status', Status::Active)
            ->exists();
    }

    public function message(): string
    {
        return "The :attribute does not exist or is not active.";
    }
}