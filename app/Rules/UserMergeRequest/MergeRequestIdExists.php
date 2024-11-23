<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;

class MergeRequestIdExists implements Rule
{
    protected $invalidId;

    public function passes($attribute, $value)
    {
        $mergeRequest = UserMergeRequest::find($value);

        if (!$mergeRequest) {
            $this->invalidId = $value;
            return false;
        }

        return true;
    }

    public function message()
    {
        return "The provided merge request ID {$this->invalidId} does not exist.";
    }
}
