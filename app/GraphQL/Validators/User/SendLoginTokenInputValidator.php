<?php

namespace App\GraphQL\Validators\User;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\User\JwtTokenIsValid;
use App\Rules\User\JwtTokenIsNotExpired;
use App\Rules\User\JwtTokenHasValidFormat;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
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
            throw new Exception("Authentication required. No user is currently logged in.");
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
            throw new Exception("Authorization header is missing.");
        }

        // Remove the 'Bearer ' prefix to get the actual token
        $token = str_replace('Bearer ', '', $authorizationHeader);

        //LOG::INFO("THE LOG IS :" . JSON_ENCODE($token));
        $finalToken = $token;

        return [
            "country_code" => [
                'required'
            ],
            "mobile" => [
                'required'
            ],
            "input.token" => [
                new JwtTokenIsValid($finalToken),
                new JwtTokenIsNotExpired($finalToken),
                new JwtTokenHasValidFormat($finalToken),
            ],
        ];
    }
}
