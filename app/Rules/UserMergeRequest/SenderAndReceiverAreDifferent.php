<?php

namespace App\Rules\UserMergeRequest;

use App\Models\PersonChild;
use Illuminate\Contracts\Validation\Rule;

class SenderAndReceiverAreDifferent implements Rule
{
    protected $senderId;

    public function __construct($senderId)
    {
        $this->senderId = $senderId;
    }

    public function passes($attribute, $value)
    {
        return $this->senderId !== $value;
    }

    public function message()
    {
        return "The sender and receiver cannot be the same.";
    }
}
