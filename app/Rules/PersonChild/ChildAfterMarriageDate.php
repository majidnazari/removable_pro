<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;
use Carbon\Carbon;

class ChildAfterMarriageDate implements Rule
{
    protected $personMarriageId;

    public function __construct($personMarriageId)
    {
        $this->personMarriageId = $personMarriageId;
    }

    public function passes($attribute, $value)
    {
        $child = Person::find($value);
        $marriage = PersonMarriage::find($this->personMarriageId);

        // Check if the marriage date is before the child’s birth date
        return $marriage && $child && Carbon::parse($marriage->marriage_date)->lt($child->birth_date);
    }

    public function message()
    {
        return 'The child’s birth date must be after the marriage date.';
    }
}
