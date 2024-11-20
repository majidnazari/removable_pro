<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;


class MergeNoDuplicateIds implements Rule
{
    protected $duplicates = [];
    protected $attributeName; // Store the attribute name

    public function passes($attribute, $value)
    {
        $this->attributeName = $attribute; // Save the attribute name for later use

        // Convert the comma-separated IDs into an array
        $idsArray = explode(',', $value);

        // Find duplicate values
        $this->duplicates = array_unique(array_diff_assoc($idsArray, array_unique($idsArray)));

        // Validation passes if there are no duplicates
        return empty($this->duplicates);
    }

    public function message()
    {
        return 'The ' . str_replace('_', ' ', $this->attributeName) . ' contains duplicate IDs: ' . implode(', ', $this->duplicates);
    }
}
