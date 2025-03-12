<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use App\Traits\FindOwnerTrait;


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
        //$user = Auth::user();
        $userOwner = $this->findOwner();
        // Fetch primary and secondary persons
        $primaryPerson = Person::find($this->primaryPersonId);
        $secondaryPerson = Person::find($this->secondaryPersonId);

        if (!$primaryPerson || !$secondaryPerson) {
            return false;
        }

        // Check if either person is an owner
        $isPrimaryOwner = $primaryPerson->is_owner;
        $isSecondaryOwner = $secondaryPerson->is_owner;

        // If either is an owner, the user must be the owner
        if (($isPrimaryOwner || $isSecondaryOwner) && !$ $userOwner->is_owner) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return "You must be an owner to merge if either person is an owner.";
    }
}
