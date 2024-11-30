<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PreventSelfMergeRequest implements Rule
{
    protected $requestId;
    protected $userSenderId;
    protected $userReceiverId;

    public function __construct($requestId)
    {
        $this->requestId = $requestId;
    }

    public function passes($attribute, $value)
    {
        //Log::info("PreventSelfMergeRequest: Checking {$attribute} with value: {$value}");

        // Fetch the UserMergeRequest record by ID
        $mergeRequest = UserMergeRequest::find($this->requestId);

        if (!$mergeRequest) {
            //Log::info("No UserMergeRequest found with id {$this->requestId}");
            return false;
        }

        $this->userSenderId = $mergeRequest->user_sender_id;
        $this->userReceiverId = $mergeRequest->user_receiver_id;

       // Log::info("User sender ID: {$this->userSenderId}, User receiver ID: {$this->userReceiverId}");

        // Validation: Ensure sender and receiver are not the same
        return $this->userSenderId !== $this->userReceiverId;
    }

    public function message()
    {
        return "A user cannot send a merge request to themselves.";
    }
}
