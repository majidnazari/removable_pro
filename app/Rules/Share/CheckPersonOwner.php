<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use Log;

class CheckPersonOwner implements Rule
{
    use AuthUserTrait;
    use FindOwnerTrait;

    public function passes($attribute, $value)
    {
        // Get the logged-in owner
        $owner = $this->findOwner();

        // If no owner is found, return false
        if (!$owner) {
            return false;
        }

        return $owner->id == (int) $value;
    }

    public function message()
    {
        return "You cannot set a favorite for another person.";
    }
}
