<?php

namespace App\Rules\UserMergeRequest;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class PersonHasValidMobile implements Rule
{
    public function passes($attribute, $value)
    {
        $person = Person::find($value);
        return $person && !empty($person->country_code) && !empty($person->mobile);
    }

    public function message()
    {
        return "The person with this mobile is not found.";
    }
}
