<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;

class JwtTokenHasValidFormat implements Rule
{
    /**
     * Check if the JWT token has a valid format (i.e., Bearer <token>).
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
        return preg_match('/^Bearer\s[a-zA-Z0-9\-\._~\+\/]+=*$/', $this->token);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The JWT token format is invalid. It should be in the format: Bearer <token>';
    }
}
