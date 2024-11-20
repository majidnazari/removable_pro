<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeIdsSenderOwnership implements Rule
{
    protected $userSenderId;
    protected $mergeIdsSender;


    public function __construct($userSenderId,$mergeIdsSender)
    {
        $this->userSenderId = $userSenderId;
        $this->mergeIdsSender = $mergeIdsSender;
    }

    public function passes($attribute, $value)
    {
        $mergeIdsSender = explode(',', $value);

        return !Person::where('creator_id', '!=', $this->userSenderId)
            ->whereIn('id', $mergeIdsSender)
            ->where('status',Status::Active)
            ->exists();
    }

    public function message()
    {
        return 'Some of the merge_ids_sender are not owned by the sender.';
    }
}
