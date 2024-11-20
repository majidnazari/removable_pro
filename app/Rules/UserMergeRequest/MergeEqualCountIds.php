<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeEqualCountIds implements Rule
{
    protected $mergeIdsReceiver;
    protected $senderCount;
    protected $receiverCount;

    public function __construct($mergeIdsReceiver)
    {
        $this->mergeIdsReceiver = $mergeIdsReceiver;
    }

    public function passes($attribute, $value)
    {
        // Count the number of IDs in sender and receiver fields
        $this->senderCount = count(explode(',', $value));
        $this->receiverCount = count(explode(',', $this->mergeIdsReceiver));

        // Validation passes if the counts are equal
        return $this->senderCount === $this->receiverCount;
    }

    public function message()
    {
        return 'The count of merge_ids_sender (' . $this->senderCount . ') and merge_ids_reciver (' . $this->receiverCount . ') must be equal.';
    }
}
