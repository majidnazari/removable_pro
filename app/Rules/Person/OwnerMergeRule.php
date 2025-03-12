<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
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
        Log::info("The owner is: " . json_encode($userOwner) . " and the owner person id is :" . $userOwner->id);
        Log::info("The primary  is: " . $this->primaryPersonId);
        Log::info("The secondary  is: " . $this->secondaryPersonId);

        // If there is no user owner, skip the check
        if (!$userOwner) {
            return true;
        }

        // If the user is an owner, they must match both primary and secondary person IDs
        if ($userOwner->is_owner) {
            if (($userOwner->id != $this->primaryPersonId) && ($userOwner->id != $this->secondaryPersonId)) {
                Log::error("Merge failed: User is an owner but does not match both persons.");
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return "If you are an owner, you must be the same person as both the primary and secondary person.";
    }
}
