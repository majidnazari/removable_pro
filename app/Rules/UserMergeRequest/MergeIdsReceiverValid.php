<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeIdsReceiverValid implements Rule
{
    protected $userSenderId;
    protected $mergeIdsReciver;

    public function __construct($userSenderId,$mergeIdsReciver)
    {
        $this->userSenderId = $userSenderId;
        $this->mergeIdsReciver = $mergeIdsReciver;
    }

    public function passes($attribute, $value)
    {
        $mergeIdsReceiver = explode(',', $value);
        $receiverCreatorId = UserMergeRequest::where('creator_id', $this->mergeIdsReciver)
        ->where('status',MergeStatus::Active)
        ->first()->creator_id;

        return !Person::where('creator_id', '!=', $receiverCreatorId)
            ->whereIn('id', $mergeIdsReceiver)
            ->where('status',Status::Active)
            ->exists();
    }

    public function message()
    {
        return 'Some of the merge_ids_reciver are invalid for the receiver.';
    }
}
