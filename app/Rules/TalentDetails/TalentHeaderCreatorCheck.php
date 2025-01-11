<?php

namespace App\Rules\TalentDetails;

use App\Models\TalentHeader;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;


class TalentHeaderCreatorCheck implements Rule
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
        $loggedInUserId = $this->getUser();

        // Find the TalentHeader by ID
        $talentHeader = TalentHeader::find($value);

        if (!$talentHeader) {
            $this->errorMessage = 'The specified talent header does not exist.';
            return false;
        }

        // Check if the creator_id matches the logged-in user
        if ($talentHeader->creator_id !== $loggedInUserId) {
            $this->errorMessage = 'You do not have permission to use this talent header.';
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
        return $this->errorMessage ?: 'The talent header validation failed.';
    }
}
