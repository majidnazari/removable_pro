<?php

namespace App\Rules\Person;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class MergePersonsRule implements Rule
{
    protected $primaryPersonId;
    protected $secondaryPersonId;

    public function __construct($primaryPersonId, $secondaryPersonId)
    {
        $this->primaryPersonId = $primaryPersonId;
        $this->secondaryPersonId = $secondaryPersonId;
    }

    public function passes($attribute, $value): bool
    {
        $primaryPerson = Person::find($this->primaryPersonId);
        $secondaryPerson = Person::find($this->secondaryPersonId);

        if (!$primaryPerson || !$secondaryPerson) {
            return false;
        }

        // Check if both persons have the same gender
        if ($primaryPerson->gender !== $secondaryPerson->gender) {
            return false;
        }

        // Check if both persons are owners
        if ($primaryPerson->is_owner && $secondaryPerson->is_owner) {
            return false;
        }

        // Check if both persons are the same
        if ($primaryPerson->id === $secondaryPerson->id) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "The selected persons cannot be merged due to incompatible conditions.";
    }
}