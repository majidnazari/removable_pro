<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\FindOwnerTrait;
use App\Models\Person;


trait BloodyPersonAncestry
{
    use FindOwnerTrait;
    public function getBloodyPersonAncestry($user_id, $depth = 3)
    {
        
        // If no relationships, return only the user's own ancestry
        $minePerson = $this->findOwner($user_id);
        if (!$minePerson) {
            return null;
        }

        [$mineAncestry, $rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
       
        return [
            'mine' => $mineAncestry,
            'heads' => $rootAncestors
        ];
    }
    public function getBloodyPersonAncestryAccordingPersonId($personId, $depth = 3)
    {
        
        // If no relationships, return only the user's own ancestry
        $minePerson =Person::where('id',$personId)->first();
        if (!$minePerson) {
            return null;
        }

        [$mineAncestry, $rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
       
        return [
            'mine' => $mineAncestry,
            'heads' => $rootAncestors
        ];
    }
}
