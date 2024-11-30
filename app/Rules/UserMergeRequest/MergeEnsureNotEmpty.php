<?php

namespace App\Rules\UserMergeRequest;

use Illuminate\Contracts\Validation\Rule;

class MergeEnsureNotEmpty implements Rule
{
    protected $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function passes($attribute, $value)
    {
        if (is_null($value) || trim($value) === '' || empty(explode(',', $value))) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return "The {$this->fieldName} cannot be empty. Please provide valid IDs.";
    }
}
