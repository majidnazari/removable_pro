<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Traits\FindOwnerTrait;
use Log;

class OwnerMergeRule implements Rule
{
    use FindOwnerTrait;

    protected $primaryPersonId;
    protected $secondaryPersonId;

    public function __construct($primaryPersonId, $secondaryPersonId)
    {
        $this->primaryPersonId = $primaryPersonId;
        $this->secondaryPersonId = $secondaryPersonId;
    }

    public function passes($attribute, $value)
    {
        // Get the actual owner user
        $userOwner = $this->findOwner();
        //       Log::info("User Owner: " . json_encode($userOwner));

        // Fetch primary and secondary persons
        $primaryPerson = Person::find($this->primaryPersonId);
        $secondaryPerson = Person::find($this->secondaryPersonId);

        if (!$primaryPerson || !$secondaryPerson) {
            return false;
        }

        // Check if either person is an owner
        $isPrimaryOwner = $primaryPerson->is_owner;
        $isSecondaryOwner = $secondaryPerson->is_owner;

        // If neither person is an owner, allow the merge
        if (!$isPrimaryOwner && !$isSecondaryOwner) {
            return true;
        }

        // If either person is an owner, check userOwner
        if ($userOwner && $userOwner->is_owner) {
            if ($userOwner->id != $this->primaryPersonId && $userOwner->id != $this->secondaryPersonId) {
                Log::error("Merge failed: User is an owner but does not match primary or secondary person.");
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return "If either the primary or secondary person is an owner, you must be the same person as one of them to merge.";
    }
}
