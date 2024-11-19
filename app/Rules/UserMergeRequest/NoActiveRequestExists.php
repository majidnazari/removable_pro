<?php

namespace App\Rules\UserMergeRequest;

use Illuminate\Contracts\Validation\Rule;
use App\Models\UserMergeRequest;
use App\GraphQL\Enums\RequestStatusSender;


use Log;

class NoActiveRequestExists implements Rule
{
    protected $senderId;

    public function __construct($senderId)
    {
        $this->senderId = $senderId;
    }

    public function passes($attribute, $value)
    {
        return !UserMergeRequest::where('user_sender_id', $this->senderId)
            ->where('request_status_sender', RequestStatusSender::Active->value)
            ->exists();
    }

    public function message()
    {
        return "You already have one active request.";
    }
}
