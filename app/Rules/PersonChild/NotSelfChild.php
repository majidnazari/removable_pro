<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;

class NotSelfChild implements Rule
{
    protected $personChildId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $personChildId  The ID of the PersonChild record
     * @return void
     */
    public function __construct($personChildId)
    {
        $this->personChildId = $personChildId;
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
        // Check if the child_id is the same as the personChildId (self-reference)
        return $value != $this->personChildId;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person cannot be their own child.';
    }
}
