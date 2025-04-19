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
        if ($value === null || trim($value) === '') {
            return false;
        }

        // Ensure at least one valid (non-empty) ID
        $ids = array_filter(explode(',', $value), fn($id) => trim($id) !== '');
        return count($ids) > 0;
    }

    public function message()
    {
        return "The {$this->fieldName} cannot be empty. Please provide valid IDs.";
    }
}
