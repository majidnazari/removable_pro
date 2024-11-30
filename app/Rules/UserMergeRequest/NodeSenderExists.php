<?php

namespace App\Rules\UserMergeRequest;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class NodeSenderExists implements Rule
{
    public function passes($attribute, $value)
    {
        return Person::find($value) !== null;
    }

    public function message()
    {
        return 'The selected node sender does not exist.';
    }
}
