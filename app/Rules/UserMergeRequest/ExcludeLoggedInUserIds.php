<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class ExcludeLoggedInUserIds implements Rule
{
    protected $loggedInUserId;
    protected $invalidIds = [];

    public function __construct($loggedInUserId)
    {
        $this->loggedInUserId = $loggedInUserId;
    }

    public function passes($attribute, $value)
    {
        $this->attributeName = $attribute; 
        // Convert the comma-separated IDs into an array
        $idsArray = explode(',', $value);

        // Check if the logged-in user's ID exists in the array
        $this->invalidIds = array_filter($idsArray, fn($id) => $id == $this->loggedInUserId);

        // Validation passes if the logged-in user's ID is not found
        return empty($this->invalidIds);
    }

    public function message()
    {
        return 'The ' . str_replace('_', ' ', $this->attributeName) . ' cannot include your own ID (' . $this->loggedInUserId . '). Invalid IDs: ' . implode(', ', $this->invalidIds);
    }
}
