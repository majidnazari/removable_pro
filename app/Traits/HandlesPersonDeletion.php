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

    public function canDeletePerson($userId, $personId)
    {
        Log::info("Checking if user {$userId} can delete person {$personId}");

        $parentIds = $this->getParentIds($personId);
        $spouseIds = $this->getSpouseIds($personId);
        $childrenIds = $this->getChildrenIds([$personId]);

        $peopleIds = array_unique(array_merge([$personId], $parentIds, $spouseIds, $childrenIds));
        $ownerIds = $this->getOwnerIds($peopleIds);
        $userIds = $this->getUserIds($ownerIds);

        Log::info("Small clan people IDs: " . json_encode($peopleIds));
        Log::info("Owner IDs in small clan: " . json_encode($ownerIds));
        Log::info("User IDs in small clan: " . json_encode($userIds));

        $person = Person::find($personId);
        if (!$person) {
            return $this->errorResponse("Person-DELETE-PERSON_NOT_FOUND");
        }

        if ($person->is_owner) {
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_OWNER");
        }

        if (!empty($childrenIds)) {
            return $this->errorResponse("Person-DELETE-HAS_CHILDREN");
        }



        if (!empty($spouseIds)) {
            $spouseOwner = Person::whereIn('id', $spouseIds)->where('is_owner', true)->exists();
            if ($spouseOwner) {
                return $this->errorResponse("Person-DELETE-CANNOT_DELETE_SPOUSE_OWNER");
            }
        }

        if (!in_array($userId, $userIds)) {
            return $this->errorResponse("Person-DELETE-YOU_DONT_HAVE_PERMISSION");
        }

        return true;
    }

    protected function getParentIds($personId)
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
            Log::info("No parent relation found for person {$personId}.");
            return [];
        }

        $parentMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        if ($parentMarriage) {
            $parentIds = [$parentMarriage->man_id, $parentMarriage->woman_id];
            Log::info("Parent IDs for person {$personId}: " . json_encode($parentIds));
            return $parentIds;
        } else {
            Log::info("No active parent marriage found for person {$personId}.");
            return [];
        }
    }

    protected function getSpouseIds($personId)
    {
        $getSpouseIds = PersonMarriage::where('man_id', $personId)
            ->orWhere('woman_id', $personId)
            ->where('status', Status::Active)
            //->pluck('id')
            ->pluck('woman_id', 'man_id')
            ->values()
            ->toArray();

        Log::info("the getSpouseIds {" . json_encode($getSpouseIds) . "} ");

        return $getSpouseIds;
    }

    protected function getChildrenIds(array $parentIds)
    {
        if (empty($parentIds)) {
            Log::info("No parent IDs provided for children and spouses retrieval.");
            return [];
        }

        $parentMarriages = PersonMarriage::whereIn('man_id', $parentIds)
            ->orWhereIn('woman_id', $parentIds)
            ->get(['id', 'man_id', 'woman_id']);

        if ($parentMarriages->isEmpty()) {
            Log::info("No parent marriages found for parent IDs: " . json_encode($parentIds));
            return [];
        }

        $childrenIds = [];
        $spouseIds = [];

        foreach ($parentMarriages as $marriage) {
            $children = PersonChild::where('person_marriage_id', $marriage->id)
                ->join('people', 'person_children.child_id', '=', 'people.id') // Join with Person model
                ->get(['person_children.child_id', 'people.gender']); // Fetch gender from Person

            if (!$children->isEmpty()) {
                $childrenIds = array_merge($childrenIds, $children->pluck('child_id')->toArray());

                $maleChildren = $children->where('gender', 1)->pluck('child_id')->toArray();
                $femaleChildren = $children->where('gender', 0)->pluck('child_id')->toArray();

                $spouses = PersonMarriage::whereIn('man_id', $maleChildren)
                    ->orWhereIn('woman_id', $femaleChildren)
                    ->get(['man_id', 'woman_id']);

                $spouseIds = array_merge($spouseIds, $spouses->pluck('man_id')->toArray(), $spouses->pluck('woman_id')->toArray());
            }
        }


        $allIds = array_unique(array_merge($childrenIds, $spouseIds));
        Log::info("Children and spouse IDs for parent IDs: " . json_encode($allIds));
        return $allIds;
    }

    protected function getOwnerIds($peopleIds)
    {
        $getOwnerIds = Person::whereIn('id', $peopleIds)->where('is_owner', true)->pluck('id')->toArray();

        return $getOwnerIds;
    }

    protected function getUserIds($ownerIds)
    {
        $getUserIds = Person::whereIn('id', $ownerIds)->pluck('creator_id')->toArray();
        return $getUserIds;
    }

    protected function errorResponse($message)
    {
        Log::error("Error response triggered: {$message}");
        return Error::createLocatedError($message);
    }
}
