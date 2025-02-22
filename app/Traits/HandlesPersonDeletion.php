<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use App\GraphQL\Enums\Status;

trait HandlesPersonDeletion
{
    use FindOwnerTrait;
    use SmallClanTrait;

    /**
     * Checks if a person can be deleted by ensuring all related entities can be deleted.
     */
    public function canDeletePerson($userId, $personId, $checkedIds = [])
    {
        Log::info("Checking deletion for person {$personId}, checked IDs: " . json_encode($checkedIds));

        if (in_array($personId, $checkedIds)) {
            Log::warning("Preventing infinite loop for person {$personId}");
            return true;
        }

        $checkedIds[] = $personId;
        $person = Person::find($personId);
        if (!$person) {
            return $this->errorResponse("Person-DELETE-PERSON_NOT_FOUND", $personId);
        }

        $clanOwnerIds = $this->getAllOwnerIdsSmallClan($personId);
        $clanUserIds = $this->getAllUserIdsSmallClan($personId);

        Log::info("the all of clanUserIds in  small clan of person {$personId} are:" . json_encode($clanUserIds));

        if (count($clanUserIds) > 0 && (!in_array($userId, $clanUserIds))) {

            // if (count($clanUserIds)>0)
            // {
            return $this->errorResponse("Person-DELETE-NOT_AUTHORIZED", $personId);

            //}
        }

        if ($person->is_owner && count($clanOwnerIds) > 1) {
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_OWNER", $personId);
        }

        $parentIds = $this->getParentIds($personId);
        $spouseIds = $this->getSpouseIds($personId, $person->gender);
        $childrenIds = $this->getChildrenIds($spouseIds);

        if (count($childrenIds) > 1) {
            return $this->errorResponse("Person-DELETE-HAS_MULTIPLE_CHILDREN", $personId);
        }

        if (count($childrenIds) == 1 && count($parentIds) == 0) {

            // Check if all spouses are users
            //$grandParents = $this->getParentIds($parentIds[0]);
            //if (empty($grandParents)) {
            //Log::info("Deleting person {$personId} as they have one child and parent with no parents.");
            Log::info("Deleting person {$personId} as they have one child and no parent but the spouse check it in small clan ");
            $allSpouses = $this->allSpouses($personId,$person->gender);

            Log::info("can delete marriage relation of  person {$personId} with these people". json_encode($allSpouses));



            // foreach ($spouseIds as $spouseId) {
            //     if (!$this->canDeletePerson($userId, $spouseId, $checkedIds)) {
            //         return $this->errorResponse("Person-DELETE-CANNOT_DELETE_SPOUSE", $spouseId);
            //     }
            // }
            //return true;
            //}
        }



        // if (!empty($parentIds)) {
        //     $firstParentId = array_shift($parentIds);
        //     if (!$this->canDeletePerson($userId, $firstParentId, $checkedIds)) {
        //         return $this->errorResponse("Person-DELETE-CANNOT_DELETE_PARENT", $firstParentId);
        //     }
        // }

        Log::info("Person {$personId} can be deleted.");
        return true;
    }



    /**
     * Get parent IDs of a person from the PersonChild table
     */
    protected function getParentIds($personId)
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
            Log::info("No parents found for person {$personId}.");
            return [];
        }

        $parentMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        $parents = $parentMarriage ? array_filter([$parentMarriage->man_id, $parentMarriage->woman_id]) : [];

        Log::info("Found parents for person {$personId}: " . json_encode($parents));
        return $parents;
    }

    /**
     * Get all spouses of a person from the PersonMarriage table
     */
    public function getSpouseIds($personId, $isMale)
    {
        // $person = Person::find($personId);

        // if (!$person) {
        //     Log::error("Person ID {$personId} not found while retrieving spouses.");
        //     return [];
        // }

        // $isMale = $person->gender; // Adjust based on your gender column

        $getSpouseIds = PersonMarriage::where($isMale ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            //->pluck($isMale ? 'woman_id' : 'man_id')
            ->pluck('id')
            ->toArray();

        Log::info("the getSpouseIds  is :" . json_encode($getSpouseIds));
        return $getSpouseIds;
    }

    /**
     * Get all children of a given set of parent IDs
     */
    protected function getChildrenIds($spouseIds)
    {
        $getChildrenIds = PersonChild::where('person_marriage_id', $spouseIds)
            ->pluck('child_id')
            ->toArray();
        Log::info("the getChildrenIds is :" . json_encode($getChildrenIds));

        return $getChildrenIds;
    }


    protected function allSpouses($personId,$gender)
    {
        return PersonMarriage::where($gender==1 ? 'man_id' : 'woman_id', $personId)->where('is_user', true)->get();
    }

    /**
     * Returns an error response for GraphQL
     */
    protected function errorResponse($message, $personId)
    {
        Log::error("Error response triggered for person {$personId}: {$message}");
        return Error::createLocatedError($message);
    }
}
