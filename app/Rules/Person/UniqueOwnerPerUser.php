<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;

class UniqueOwnerPerUser implements Rule
{
    protected $userId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $userId  The ID of the user creating the person record
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if a Person record already exists with is_owner=true for this user_id
        return !Person::where('creator_id', $this->userId)
                      ->where('is_owner', true)
                      ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Each user can only create one person with is_owner set to true.';
    }
}

