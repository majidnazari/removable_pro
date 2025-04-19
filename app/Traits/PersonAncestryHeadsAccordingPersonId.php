<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\FindOwnerTrait;
use App\Models\Person;


trait PersonAncestryHeadsAccordingPersonId
{
    use FindOwnerTrait;
    public function getPersonAncestryHeadsAccordingPersonId($personId, $depth = 3)
    {

        // If no relationships, return only the user's own ancestry
        $minePerson = Person::where('id', $personId)->first();
        if (!$minePerson) {
            return null;
        }

        $rootAncestors = $minePerson->getFullBinaryAncestryheads($depth);

        return [
            'heads' => $rootAncestors
        ];
    }
}
