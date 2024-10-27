<?php

namespace App\Rules\PersonMarriage;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;

class NotCloseRelativeMarriage implements Rule
{
    protected $manId;
    protected $womanId;

    public function __construct($manId, $womanId)
    {
        $this->manId = $manId;
        $this->womanId = $womanId;
    }

    public function passes($attribute, $value)
    {
        $man = Person::find($this->manId);
        $woman = Person::find($this->womanId);

        if (!$man || !$woman) {
            return true; // If either person doesn't exist, let validation pass to avoid blocking
        }

        // Check if they are direct ancestors of each other
        $manAncestors = $man->ancestors();
        $womanAncestors = $woman->ancestors();

        // Check if either person is an ancestor of the other
        if ($manAncestors->contains('id', $woman->id) || $womanAncestors->contains('id', $man->id)) {
            return false;
        }

        // Check if they share any common ancestor (sibling relationship)
        if ($manAncestors->intersect($womanAncestors)->isNotEmpty()) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Marriage between close relatives is not allowed.';
    }
}
