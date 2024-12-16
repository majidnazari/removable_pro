<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Exception;

trait AuthUserTrait
{
    protected $userId;
    protected $user;

/**
     * Get the authenticated user's ID.
     *
     * @return int|null
     */
    protected function getUserId()
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        return  $this->userId;//auth()->guard('api')->user()?->id; // Use null safe operator to handle unauthenticated cases
    }

    protected function getUser()
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->user = $user;
        return  $this->user;//auth()->guard('api')->user()?->id; // Use null safe operator to handle unauthenticated cases
    }
}

