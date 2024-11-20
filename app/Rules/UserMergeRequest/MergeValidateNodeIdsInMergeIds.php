<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class MergeValidateNodeIdsInMergeIds implements Rule
{
    protected $userMergeRequestId;
    protected $mergeIdsSender;
    protected $mergeIdsReceiver;
    protected $missingIds = [];

    public function __construct($userMergeRequestId, $mergeIdsSender, $mergeIdsReceiver)
    {
        $this->userMergeRequestId = $userMergeRequestId;
        $this->mergeIdsSender = $mergeIdsSender;
        $this->mergeIdsReceiver = $mergeIdsReceiver;
    }

    public function passes($attribute, $value)
    {
        // Fetch the user merge request by ID
        $userMergeRequest = UserMergeRequest::where('id',$this->userMergeRequestId)->where('status',MergeStatus::Active)->first();

        if (!$userMergeRequest) {
            return false;
        }

        // Extract node_sender_id and node_reciver_id
        $nodeSenderId = $userMergeRequest->node_sender_id;
        $nodeReceiverId = $userMergeRequest->node_reciver_id;

        // Convert merge IDs to arrays
        $mergeIdsSenderArray = explode(',', $this->mergeIdsSender);
        $mergeIdsReceiverArray = explode(',', $this->mergeIdsReceiver);

        // Check if both node_sender_id and node_reciver_id exist in their respective arrays
        if (!in_array($nodeSenderId, $mergeIdsSenderArray)) {
            $this->missingIds['sender'] = $nodeSenderId;
        }

        if (!in_array($nodeReceiverId, $mergeIdsReceiverArray)) {
            $this->missingIds['receiver'] = $nodeReceiverId;
        }

        // Validation passes if no missing IDs
        return empty($this->missingIds);
    }

    public function message()
    {
        $messages = [];
        if (isset($this->missingIds['sender'])) {
            $messages[] = "The node_sender_id ({$this->missingIds['sender']}) must be included in merge_ids_sender.";
        }
        if (isset($this->missingIds['receiver'])) {
            $messages[] = "The node_reciver_id ({$this->missingIds['receiver']}) must be included in merge_ids_reciver.";
        }
        return implode(' ', $messages);
    }
}
