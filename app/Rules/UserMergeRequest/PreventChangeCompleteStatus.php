<?php

namespace App\Rules\UserMergeRequest;

use App\GraphQL\Enums\MergeStatus;
use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;


class PreventChangeCompleteStatus implements Rule
{
    protected $mergeRequestId;

    public function __construct($mergeRequestId)
    {
        $this->mergeRequestId = $mergeRequestId;
    }

    public function passes($attribute, $value)
    {
        // Fetch the merge request
        $mergeRequest = UserMergeRequest::find($this->mergeRequestId);

        if (!$mergeRequest) {
            return false; // Validation fails if the merge request is not found
        }

        // Prevent changing the status if it is already Complete
        return !($mergeRequest->status == MergeStatus::Complete->value); // Assuming 4 represents the Complete status
    }

    public function message()
    {
        return 'The status cannot be changed because it is already set to Complete.';
    }
}
