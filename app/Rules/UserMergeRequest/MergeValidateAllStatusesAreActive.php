<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;

class MergeValidateAllStatusesAreActive implements Rule
{
    protected $mergeRequestId;
    protected $userId;
    protected $invalidStatuses = [];

    public function __construct($mergeRequestId, $userId)
    {
        $this->mergeRequestId = $mergeRequestId;
        $this->userId = $userId;
    }

    public function passes($attribute, $value)
    {
        $mergeRequest = UserMergeRequest::find($this->mergeRequestId);

        if (!$mergeRequest) {
            return false; // Fail if merge request not found
        }

        // Ensure the logged-in user is the sender
        if ($mergeRequest->user_sender_id !== $this->userId) {
            return false; // Fail if the logged-in user is not the sender
        }

        // Check statuses
        $statuses = [
            'request_status_sender' => $mergeRequest->request_status_sender,
            'request_status_receiver' => $mergeRequest->request_status_receiver,
            'merge_status_sender' => $mergeRequest->merge_status_sender,
            'merge_status_receiver' => $mergeRequest->merge_status_receiver,
            //'status' => $mergeRequest->status,
        ];

        foreach ($statuses as $key => $status) {
            if ($status !== MergeStatus::Active->value) { // Assuming 1 represents "Active"
                $this->invalidStatuses[$key] = $status;
            }
        }

        return empty($this->invalidStatuses);
    }

    public function message()
    {
        if (!empty($this->invalidStatuses)) {
            $invalidDetails = implode(', ', array_map(
                fn($key, $value) => "$key: $value",
                array_keys($this->invalidStatuses),
                $this->invalidStatuses
            ));

            return "The following statuses are not active: $invalidDetails.";
        }

        return 'Validation failed for the merge request.';
    }
}
