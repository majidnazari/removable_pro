<?php

namespace App\Traits;

trait AuthUserTrait
{
/**
     * Get the authenticated user's ID.
     *
     * @return int|null
     */
    protected function getUserId()
    {
        return auth()->guard('api')->user()?->id; // Use null safe operator to handle unauthenticated cases
    }
}

