<?php

namespace App\Rules\UserMergeRequest;

use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\Status;

use App\Models\Person;

class ReceiverHasOwner implements Rule
{
    protected $receiverId;

    public function __construct($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function passes($attribute, $value)
    {
        return Person::where('creator_id', $this->receiverId)
            ->where('is_owner', true)
            ->where('status', Status::Active)
            ->exists();
    }

    public function message()
    {
        return "The receiver's owner is not found.";
    }
}

