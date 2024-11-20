<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class MustIncludeLoggedInUserId implements Rule
{ protected $loggedInUserId;
    protected $attributeName;

    public function __construct($loggedInUserId)
    {
        $this->loggedInUserId = $loggedInUserId;
    }

    public function passes($attribute, $value)
    {
        $this->attributeName = $attribute; // Store the attribute name for later use

        // Convert the comma-separated IDs into an array
        $idsArray = explode(',', $value);

        // Check if the logged-in user's ID is present in the array
        return in_array($this->loggedInUserId, $idsArray);
    }

    public function message()
    {
        return 'The ' . str_replace('_', ' ', $this->attributeName) . ' must include your own ID (' . $this->loggedInUserId . ').';
    }
}
