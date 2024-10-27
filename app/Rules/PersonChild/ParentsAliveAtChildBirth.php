<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;

class ParentsAliveAtChildBirth implements Rule
{
    protected $personMarriageId;

    public function __construct($personMarriageId)
    {
        $this->personMarriageId = $personMarriageId;
    }

    public function passes($attribute, $value)
    {
        $child = Person::find($value);
        $marriage = PersonMarriage::with(['Man', 'Woman'])->find($this->personMarriageId);

        // Check if both parents were alive at the time of the child’s birth
        if ($marriage && $child) {
            if ($marriage->Man && $marriage->Man->death_date && $marriage->Man->death_date < $child->birth_date) {
                return false;
            }
            if ($marriage->Woman && $marriage->Woman->death_date && $marriage->Woman->death_date < $child->birth_date) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'One or both parents were not alive at the time of the child’s birth.';
    }
}
