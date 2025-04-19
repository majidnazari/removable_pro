<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Exception;
use App\Exceptions\CustomValidationException;

use Log;

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
            // throw new Exception("Authentication required. No user is currently logged in.");

            $message = "Authentication required. No user is currently logged in.";
            $endUserMessage = "احراز هویت لازم است. هیچ کاربری در حال حاضر وارد نشده است.";
            $statusCode = 401;

            throw new CustomValidationException($message, $endUserMessage, $statusCode);
        }

        $this->userId = $user->id;
        return $this->userId;//auth()->guard('api')->user()?->id; // Use null safe operator to handle unauthenticated cases
    }

    protected function getUser()
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            //throw new Exception("Authentication required. No user is currently logged in.");

            $message = "Authentication required. No user is currently logged in.";
            $endUserMessage = "احراز هویت لازم است. هیچ کاربری در حال حاضر وارد نشده است.";
            $statusCode = 401;

            throw new CustomValidationException($message, $endUserMessage, $statusCode);
        }

        $this->user = $user;
        return $this->user;//auth()->guard('api')->user()?->id; // Use null safe operator to handle unauthenticated cases
    }
}

