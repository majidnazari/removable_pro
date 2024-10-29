<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;

class DistinctParents implements Rule
{
    protected $fatherId;
    protected $motherId;

    /**
     * Create a new rule instance.
     *
     * @param mixed $fatherId
     * @param mixed $motherId
     */
    public function __construct($fatherId, $motherId)
    {
        $this->fatherId = $fatherId;
        $this->motherId = $motherId;
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
        return $this->fatherId !== $this->motherId;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person cannot be both father and mother.';
    }
}
