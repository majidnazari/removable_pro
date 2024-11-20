<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeIdsCountMatch implements Rule
{
    public function passes($attribute, $value)
    {
        $mergeIdsReceiver = explode(',', request()->input('merge_ids_reciver'));
        $mergeIdsSender = explode(',', request()->input('merge_ids_sender'));

        return count($mergeIdsReceiver) === count($mergeIdsSender);
    }

    public function message()
    {
        return 'The number of merge_ids_reciver and merge_ids_sender must match.';
    }
}
