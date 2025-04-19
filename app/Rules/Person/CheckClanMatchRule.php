<?php

namespace App\Rules\Person;

use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use Log;

class CheckClanMatchRule implements Rule
{
    use AuthUserTrait;
    protected $primaryPersonId;
    protected $secondaryPersonId;
    protected $loggedInClanId;

    public function __construct($primaryPersonId, $secondaryPersonId)
    {
        $this->primaryPersonId = $primaryPersonId;
        $this->secondaryPersonId = $secondaryPersonId;
        $this->loggedInClanId = $this->getUser()->clan_id;// Auth::user()->clan_id; // Assumes the logged-in user has a clan_id field

    }

    public function passes($attribute, $value): bool
    {

        // Get the creator IDs for the primary and secondary persons
        $primaryCreatorId = Person::find($this->primaryPersonId)?->creator_id;
        $secondaryCreatorId = Person::find($this->secondaryPersonId)?->creator_id;

        if (!$primaryCreatorId || !$secondaryCreatorId) {
            return false; // Fail if either creator is not found
        }

        // Get the clan IDs of the creators
        $primaryClanId = User::find($primaryCreatorId)?->clan_id;
        $secondaryClanId = User::find($secondaryCreatorId)?->clan_id;

        // Check if all clan IDs match
        return $this->loggedInClanId === $primaryClanId && $this->loggedInClanId === $secondaryClanId;
    }

    public function message(): string
    {
        return "The logged-in user's clan does not match the clans of the selected persons.";
    }
}
