<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeNoDuplicateMergeRequest implements Rule
{
    public function passes($attribute, $value)
    {
        $mergeIdsReceiver = explode(',', request()->input('merge_ids_reciver'));
        $mergeIdsSender = explode(',', request()->input('merge_ids_sender'));

        return !UserMergeRequest::whereJsonContains('merge_ids_reciver', $mergeIdsReceiver)
            ->whereJsonContains('merge_ids_sender', $mergeIdsSender)
            ->where('status', MergeStatus::Active)
            ->exists();
    }

    public function message()
    {
        return 'A duplicate merge request already exists.';
    }
}
