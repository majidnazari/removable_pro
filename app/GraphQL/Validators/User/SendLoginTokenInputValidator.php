<?php

namespace App\GraphQL\Validators\User;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\User\JwtTokenIsValid;
use App\Rules\User\JwtTokenIsNotExpired;
use App\Rules\User\JwtTokenHasValidFormat;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Exceptions\CustomValidationException;

use Exception;

use Log;

class SendLoginTokenInputValidator extends Validator
{
    use AuthUserTrait;
    protected $userId;

    public function __construct()
    {
        // Ensure a user is authenticated
        $user = $this->getUser();// Auth::guard('api')->user();

        if (!$user) {
            throw new CustomValidationException("Authentication required. No user is currently logged in.", "احراز هویت لازم است. هیچ کاربری در حال حاضر وارد نشده است.", 403);

        }

        $this->userId = $user->id;
    }

    /**
     * Return the validation rules for the input.
     */
    public function rules(): array
    {
        // Extract the token from the Authorization header using request() helper
        $authorizationHeader = request()->header('Authorization');

        if (!$authorizationHeader) {
            throw new CustomValidationException("Authorization header is missing.", "سرصفحه مجوز وجود ندارد.", 400);

        }

        $token = str_replace('Bearer ', '', $authorizationHeader);

        $finalToken = $token;

        return [
            "country_code" => [
                'required'
            ],
            "mobile" => [
                'required'
            ],

        ];
    }
}
