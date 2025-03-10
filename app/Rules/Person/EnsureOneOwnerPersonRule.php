<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Traits\FindOwnerTrait;

class EnsureOneOwnerPersonRule implements Rule
{
    use FindOwnerTrait;

    protected $primaryPersonId;
    protected $secondaryPersonId;
    protected $errorMessage = '';

    public function __construct($primaryPersonId, $secondaryPersonId)
    {
        $this->primaryPersonId = $primaryPersonId;
        $this->secondaryPersonId = $secondaryPersonId;
    }
    public function passes($attribute, $value)
    {
    
        // Get the owner's person ID of the logged-in user
        $ownerPersonId = $this->findOwner();

        if (!$ownerPersonId) {
            return false; // User must have an owner person ID
        }

        // Ensure that one of them is the ownerPersonId, and they are different
        return ($this->primaryPersonId  == $ownerPersonId && $this->secondaryPersonId != $ownerPersonId) ||
               ($this->secondaryPersonId == $ownerPersonId && $this->primaryPersonId  != $ownerPersonId);
    }

    public function message()
    {
        return "One of the persons must be your owner person, and the other must be different.";
    }
}
