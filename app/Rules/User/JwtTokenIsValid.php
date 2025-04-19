<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Firebase\JWT\JWT;
use Exception;

class JwtTokenIsValid implements Rule
{
    /**
     * Validate the JWT token.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    protected $token;

    // Accept the token to be validated
    public function __construct($token)
    {
        $this->token = $token;
    }
    public function passes($attribute, $value)
    {
        try {
            // Attempt to decode the token
            //JWT::decode($this->token, env('JWT_SECRET'), ['RS256']); // Specify the algorithm used
            return true;
        } catch (Exception $e) {
            return false; // If an error occurs (invalid token), return false
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided JWT token is invalid.';
    }
}
