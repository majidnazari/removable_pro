<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Firebase\JWT\JWT;
use Exception;

class JwtTokenIsNotExpired implements Rule
{
    /**
     * Validate the expiration of the JWT token.
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
            $decoded = JWT::decode($this->token, env('JWT_SECRET'), ['RS256']);

            // Check if the token is expired
            if (isset($decoded->exp) && $decoded->exp < time()) {
                return false; // Expired token
            }

            return true;
        } catch (Exception $e) {
            return false; // Invalid token
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The JWT token has expired.';
    }
}
