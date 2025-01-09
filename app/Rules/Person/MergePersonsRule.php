<?php

namespace App\Rules\Person;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class MergePersonsRule implements Rule
{
    protected $primaryPersonId;
    protected $secondaryPersonId;
    protected $errorMessage = '';

    public function __construct($primaryPersonId, $secondaryPersonId)
    {
        $this->primaryPersonId = $primaryPersonId;
        $this->secondaryPersonId = $secondaryPersonId;
    }

    public function passes($attribute, $value): bool
    {
        $primaryPerson = Person::find($this->primaryPersonId);
        $secondaryPerson = Person::find($this->secondaryPersonId);

        if (!$primaryPerson) {
            $this->errorMessage = "The primary person does not exist.";
            return false;
        }

        if (!$secondaryPerson) {
            $this->errorMessage = "The secondary person does not exist.";
            return false;
        }

        // Check if both persons have the same gender
        if ($primaryPerson->gender !== $secondaryPerson->gender) {
            $this->errorMessage = "The selected persons do not have the same gender.";
            return false;
        }

        // Check if both persons are owners
        if ($primaryPerson->is_owner && $secondaryPerson->is_owner) {
            $this->errorMessage = "Both selected persons are owners and cannot be merged.";
            return false;
        }

        // Check if both persons are the same
        if ($primaryPerson->id === $secondaryPerson->id) {
            $this->errorMessage = "The selected persons cannot be the same.";
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->errorMessage ?: "The selected persons cannot be merged.";
    }
}
