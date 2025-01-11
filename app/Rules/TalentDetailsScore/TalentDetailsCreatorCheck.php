<?php

namespace App\Rules\TalentDetailsScore;

use App\Models\TalentDetail;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;


class TalentDetailsCreatorCheck implements Rule
{
    use AuthUserTrait;
    private $errorMessage;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Get the logged-in user ID
        $loggedInUserId = $this->getUserId();

        // Find the Talentdetails by ID
        $talentdetails = TalentDetail::find($value);

        if (!$talentdetails) {
            $this->errorMessage = 'The specified talent details does not exist.';
            return false;
        }

        // Check if the creator_id matches the logged-in user
        if ($talentdetails->creator_id !== $loggedInUserId) {
            $this->errorMessage = 'You do not have permission to use this talent details.';
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
        return $this->errorMessage ?: 'The talent details validation failed.';
    }
}
