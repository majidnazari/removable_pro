<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;


class MergeActiveRequestExists implements Rule
{
    public function passes($attribute, $value)
    {
        return UserMergeRequest::where('id', $value)->where('status', MergeStatus::Active)->exists();
    }

    public function message()
    {
        return 'The request ID is invalid or not active.';
    }
}
