<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class MergeIdsAreDifferent implements Rule
{
    protected $mergeIdsReceiver;
    protected $commonIds = [];

    public function __construct($mergeIdsReceiver)
    {
        $this->mergeIdsReceiver = $mergeIdsReceiver;
    }

    public function passes($attribute, $value)
    {
        // Convert sender and receiver IDs to arrays
        $mergeIdsSenderArray = explode(',', $value);
        $mergeIdsReceiverArray = explode(',', $this->mergeIdsReceiver);

        // Find common IDs between sender and receiver
        $this->commonIds = array_intersect($mergeIdsSenderArray, $mergeIdsReceiverArray);

        return empty($this->commonIds); // Pass if no common IDs
    }

    public function message()
    {
        return 'The merge_ids_sender and merge_ids_reciver cannot contain the same values. Duplicated IDs: ' . implode(', ', $this->commonIds);
    }
}
