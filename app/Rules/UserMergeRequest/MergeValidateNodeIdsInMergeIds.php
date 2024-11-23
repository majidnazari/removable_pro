<?php

namespace App\Rules\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusSender;
use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class MergeValidateNodeIdsInMergeIds implements Rule
{
    protected $userMergeRequestId;
    protected $mergeIdsSender;
    protected $mergeIdsReceiver;
    protected $errors = [];

    public function __construct($userMergeRequestId, $mergeIdsSender, $mergeIdsReceiver)
    {
        $this->userMergeRequestId = $userMergeRequestId;
        $this->mergeIdsSender = $mergeIdsSender;
        $this->mergeIdsReceiver = $mergeIdsReceiver;
    }

    public function passes($attribute, $value)
    {
        // Fetch the user merge request by ID
        $userMergeRequest = UserMergeRequest::where('id', $this->userMergeRequestId)
            ->where('request_status_sender', RequestStatusSender::Active)
            ->where('request_status_receiver', RequestStatusSender::Active)
            ->first();

        if (!$userMergeRequest) {
            $this->errors[] = 'The provided  merge request record  is invalid or inactive.';
            return false;
        }

        // Extract node_sender_id and node_receiver_id
        $nodeSenderId = $userMergeRequest->node_sender_id;
        $nodeReceiverId = $userMergeRequest->node_receiver_id;

        // Convert merge IDs to arrays
        $mergeIdsSenderArray = explode(',', $this->mergeIdsSender);
        $mergeIdsReceiverArray = explode(',', $this->mergeIdsReceiver);

        // Check if node_sender_id is the first element in merge_ids_sender
        if ($mergeIdsSenderArray[0] != $nodeSenderId) {
            $this->errors[] = "The node_sender_id ({$nodeSenderId}) must be the first element in merge_ids_sender.";
        }

        // Check if node_receiver_id is the first element in merge_ids_receiver
        if ($mergeIdsReceiverArray[0] != $nodeReceiverId) {
            $this->errors[] = "The node_receiver_id ({$nodeReceiverId}) must be the first element in merge_ids_receiver.";
        }

        // Validation passes if there are no errors
        return empty($this->errors);
    }

    public function message()
    {
        return implode(' ', $this->errors);
    }
}
