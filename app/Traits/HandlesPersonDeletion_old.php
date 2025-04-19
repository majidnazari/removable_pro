<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use App\GraphQL\Enums\Status;

trait HandlesPersonDeletion_old
{
    use FindOwnerTrait;

    /**
     * Determine if the user can delete the person.
     *
     * @param  int  $userId
     * @param  int  $personId
     * @return bool|Error
     */
    public function canDeletePerson($userId, $personId)
    {
        //       Log::info("Checking if user {$userId} can delete person {$personId}");

        // Fetch all people in the small clan
        $allPeopleIds = $this->getAllPeopleIdsInSmallClan($personId);
        //       Log::info("All people IDs in small clan: " . json_encode($allPeopleIds));

        // Get all owner IDs in the small clan
        $ownerIds = $this->getAllOwnerIdsInSmallClan($personId);
        //       Log::info("Owner IDs in small clan: " . json_encode($ownerIds));

        // Get all user IDs in the small clan
        $userIds = $this->getAllUserIdsInSmallClan($personId);
        //       Log::info("User IDs in small clan: " . json_encode($userIds));

        // Ensure the person exists
        $person = Person::find($personId);
        if (!$person) {
            Log::error("Person with ID {$personId} not found.");
            return $this->errorResponse("Person-DELETE-PERSON_NOT_FOUND");
        }

        // Prevent deletion of another owner (unless deleting oneself)
        if ($person->is_owner && $person->creator_id !== $userId) {
            //           Log::info("Attempting to delete another owner. User ID: {$userId}, Person ID: {$personId}");
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_OTHER_OWNER");
        }
        if ($person->is_owner && $person->creator_id == $userId) {
            //           Log::info("Attempting to delete another owner. User ID: {$userId}, Person ID: {$personId}");
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_YOUR_OWNER");
        }

        // Ensure the logged-in user has permission
        if (!in_array($userId, $userIds)) {
            //           Log::info("User {$userId} does not have permission to delete person {$personId}.");
            return $this->errorResponse("Person-DELETE-YOU_DONT_HAVE_PERMISSION");
        }

        // Prevent deletion if the person has children
        if ($this->hasChildren($personId)) {
            //           Log::info("Person {$personId} has children and cannot be deleted.");
            return $this->errorResponse("Person-DELETE-HAS_CHILDREN");
        }

        // Prevent deletion if there are other owners and the user isn't one of them
        if (!empty($ownerIds) && !in_array($userId, $ownerIds)) {
            //           Log::info("Other owners exist and user {$userId} is not one of them.");
            return $this->errorResponse("Person-DELETE-OTHER_OWNER_EXISTS");
        }

        //       Log::info("User {$userId} is authorized to delete person {$personId}.");
        return true; // Person can be deleted
    }

    /**
     * Check if the person has any children.
     *
     * @param  int  $personId
     * @return bool
     */
    protected function hasChildren($personId)
    {
        $children = $this->getAllChildren([$personId]);
        //       Log::info("Checking if person {$personId} has children: " . json_encode($children));
        return !empty($children);
    }

    /**
     * Create an error response with logging.
     *
     * @param  string  $message
     * @return Error
     */
    protected function errorResponse($message)
    {
        Log::error("Error response triggered: {$message}");
        return Error::createLocatedError($message);
    }

    /**
     * Retrieve all user IDs in the small clan.
     *
     * @param  int  $personId
     * @return array
     */
    protected function getAllUserIdsInSmallClan($personId)
    {
        $ownerIds = $this->getAllOwnerIdsInSmallClan($personId);
        //       Log::info("Retrieving user IDs for owners: " . json_encode($ownerIds));
        $users = Person::whereIn('id', $ownerIds)
            ->where('status', Status::Active)
            ->pluck('creator_id')
            ->toArray();
        //       Log::info("User IDs in small clan: " . json_encode($users));
        return $users;
    }

    /**
     * Retrieve all owner IDs in the small clan.
     *
     * @param  int  $personId
     * @return array
     */
    protected function getAllOwnerIdsInSmallClan($personId)
    {
        $people = Person::whereIn('id', $this->getAllPeopleIdsInSmallClan($personId))
            ->where('is_owner', true)
            ->get(['id']);
        $ownerIds = $people->pluck('id')->toArray();
        //       Log::info("Owner IDs in small clan: " . json_encode($ownerIds));
        return $ownerIds;
    }

    /**
     * Retrieve all people IDs in the small clan.
     *
     * @param  int  $personId
     * @return array
     */
    protected function getAllPeopleIdsInSmallClan($personId)
    {
        if (!$personId) {
            //           Log::info("No person ID provided for small clan retrieval.");
            return [];
        }

        $parentIds = $this->getParentIds($personId);
        $spouseIds = $this->getSpouseIds($personId);
        $childrenAndSpousesIds = $this->getChildrenAndSpousesIds([$personId]);
        $allDescendants = $this->getAllChildren($childrenAndSpousesIds);

        $allIds = collect([$personId])
            ->merge($parentIds)
            ->merge($spouseIds)
            ->merge($childrenAndSpousesIds)
            ->merge($allDescendants)
            ->unique()
            ->values()
            ->all();
        //       Log::info("All people IDs in small clan: " . json_encode($allIds));
        return $allIds;
    }

    /**
     * Retrieve parent IDs of the person.
     *
     * @param  int  $personId
     * @return array
     */
    protected function getParentIds($personId)
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
            //           Log::info("No parent relation found for person {$personId}.");
            return [];
        }

        $parentMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        if ($parentMarriage) {
            $parentIds = [$parentMarriage->man_id, $parentMarriage->woman_id];
            //           Log::info("Parent IDs for person {$personId}: " . json_encode($parentIds));
            return $parentIds;
        } else {
            //           Log::info("No active parent marriage found for person {$personId}.");
            return [];
        }
    }

    /**
     * Retrieve spouse IDs of the person.
     *
     * @param  int  $personId
     * @return array
     */
    protected function getSpouseIds($personId)
    {
        $spouses = PersonMarriage::where('man_id', $personId)
            ->orWhere('woman_id', $personId)
            ->where('status', Status::Active)
            ->get(['man_id', 'woman_id']);

        $spouseIds = $spouses->flatMap(function ($marriage) use ($personId) {
            return [$marriage->man_id, $marriage->woman_id];
        })->unique()->reject(function ($id) use ($personId) {
            return $id === $personId;
        })->values()->all();

        //       Log::info("Spouse IDs for person {$personId}: " . json_encode($spouseIds));
        return $spouseIds;
    }

    /**
     * Retrieve children and spouse IDs of the given parent IDs.
     *
     * @param  array  $parentIds
     * @return array
     */
    protected function getChildrenAndSpousesIds(array $parentIds)
    {
        if (empty($parentIds)) {
            //           Log::info("No parent IDs provided for children and spouses retrieval.");
            return [];
        }

        $parentMarriages = PersonMarriage::whereIn('man_id', $parentIds)
            ->orWhereIn('woman_id', $parentIds)
            ->get(['id', 'man_id', 'woman_id']);

        if ($parentMarriages->isEmpty()) {
            //           Log::info("No parent marriages found for parent IDs: " . json_encode($parentIds));
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
        //       Log::info("Children and spouse IDs for parent IDs: " . json_encode($allIds));
        return $allIds;
    }

    /**
     * Retrieve all children IDs of the given person IDs.
     *
     * @param  array  $childrenIds
     * @return array
     */
    protected function getAllChildren($childrenIds)
    {
        if (empty($childrenIds)) {
            //           Log::info("No children IDs provided for retrieving all children.");
            return [];
        }

        $allDescendants = [];
        $queue = $childrenIds;

        while (!empty($queue)) {
            $newGeneration = $this->getChildrenAndSpousesIds($queue);

            if (!empty($newGeneration)) {
                $allDescendants = array_merge($allDescendants, $newGeneration);
                $queue = $newGeneration;
            } else {
                break;
            }
        }

        $uniqueDescendants = array_unique($allDescendants);
        //       Log::info("All descendants IDs: " . json_encode($uniqueDescendants));
        return $uniqueDescendants;
    }
}