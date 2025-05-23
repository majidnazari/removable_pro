<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\FindOwnerTrait;


trait PersonAncestryHeads
{
    use FindOwnerTrait;
    public function getPersonAncestryHeads($user_id, $depth = 3)
    {
        // If no relationships, return only the user's own ancestry
        $minePerson = $this->findOwner($user_id);
        if (!$minePerson) {
            return null;
        }
        $rootAncestors = $minePerson->getFullBinaryAncestryheads($depth);
        return [
            'heads' => $rootAncestors
        ];
    }
}
