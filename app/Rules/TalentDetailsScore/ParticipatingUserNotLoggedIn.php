<?php

namespace App\Rules\TalentDetailsScore;


use App\Traits\AuthUserTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ParticipatingUserNotLoggedIn implements Rule
{
    use AuthUserTrait;
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the participating user ID is the same as the logged-in user's ID
        if ($value == $this->getUserId()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The participating user ID cannot be the same as the logged-in user.";
    }
}
