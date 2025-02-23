<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use App\GraphQL\Enums\Status;
use App\Events\ClanUpdated;
use App\Models\User;

trait HandlesPersonDeletion
{
    use FindOwnerTrait;
    use SmallClanTrait;

    /**
     * Checks if a person can be deleted by ensuring all related entities can be deleted.
     */
    public function canDeletePerson(int $userId, int $personId, array &$checkedIds = []): bool|Error
    {
        if (in_array($personId, $checkedIds)) {
            Log::warning("Skipping already checked person {$personId} to prevent infinite loop.");
            return true;
        }

        Log::info("Checking deletion for person {$personId}...");
        $checkedIds[] = $personId;

        $person = Person::find($personId);
        if (!$person) {
            return $this->errorResponse("Person-DELETE-PERSON_NOT_FOUND", $personId);
        }

        $clanUserIds = $this->getAllUserIdsSmallClan($personId);
        if (!empty($clanUserIds) && !in_array($userId, $clanUserIds)) {
            return $this->errorResponse("Person-DELETE-NOT_AUTHORIZED", $personId);
        }

        if ($person->is_owner && count($this->getAllOwnerIdsSmallClan($personId)) > 1) {
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_OWNER", $personId);
        }

        $parentIds = $this->getParentIds($personId);
        $spouseIds = $this->getSpouseIds($personId, $person->gender);
        $childrenIds = $this->getChildrenIds($spouseIds);

        // 🆕 If the person is an owner and has no relations, update their user clan_id
        if ($person->is_owner && empty($parentIds) && empty($childrenIds) && empty($spouseIds)) {
            Log::info("Person {$personId} is an owner with no relations. Updating user's clan_id...");

            $this->updateUserClanId($userId);
        }

        if ($person->is_owner && empty($parentIds) && empty($childrenIds)) {
            Log::info("Person {$personId} is an owner with no parents and no children. Removing all spouse relations...");
            $this->removeSpouseRelations($personId, $person->gender);
        }

        if ($person->is_owner && !empty($parentIds)) {
            return $this->errorResponse("Person-DELETE-OWNER_MUST_DELETE_PARENTS", $personId);
        }

        if (count($childrenIds) > 1) {
            return $this->errorResponse("Person-DELETE-HAS_MULTIPLE_CHILDREN", $personId);
        }

        if (count($childrenIds) === 1 && empty($parentIds)) {
            Log::info("Person {$personId} has one child but no parents. Checking small clan for spouse permission...");
            //Log::info("Person {$personId} has one child but no parents. Checking small clan for spouse permission...");
            //if (!$this->allSpouses($personId, $person->gender)) {
            $this->removeChildRelation($personId, $person->gender, true);
            return true;
            //}
        }

        Log::info("Person {$personId} is a child, removing parental relation.");
        $this->removeChildRelation($personId, $person->gender);

        Log::info("Person {$personId} can be deleted.");
        return true;
    }

    /**
     * Get parent IDs of a person from the PersonChild table.
     */
    protected function getParentIds(int $personId): array
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
            Log::info("No parents found for person {$personId}.");
            return [];
        }

        $parentMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        return $parentMarriage ? array_filter([$parentMarriage->man_id, $parentMarriage->woman_id]) : [];
    }

    /**
     * Get all spouse IDs of a person.
     */
    protected function getSpouseIds(int $personId, bool $isMale): array
    {
        $spouseIds = PersonMarriage::where($isMale ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck('id')
            ->toArray();

        Log::info("Spouse IDs for person {$personId}: " . json_encode($spouseIds));
        return $spouseIds;
    }

    /**
     * Get all children of a given set of spouse IDs.
     */
    protected function getChildrenIds(array $spouseIds): array
    {
        if (empty($spouseIds)) {
            return [];
        }

        $childrenIds = PersonChild::whereIn('person_marriage_id', $spouseIds)
            ->pluck('child_id')
            ->toArray();

        Log::info("Children IDs retrieved: " . json_encode($childrenIds));
        return $childrenIds;
    }

    /**
     * Check if there is an owner among all spouses.
     */
    protected function allSpouses(int $personId, bool $isMale): bool
    {
        $spouseIds = PersonMarriage::where($isMale == 0 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck($isMale ? 'woman_id' : 'man_id')
            ->toArray();

        if (empty($spouseIds)) {
            return false;
        }

        $isOwnerPresent = Person::whereIn('id', $spouseIds)
            ->where('status', Status::Active)
            ->where('is_owner', true)
            ->exists();

        return $isOwnerPresent;
    }

    /**
     * Remove parent-child relationship.
     */
    protected function removeChildRelation(int $personId, int $gender, bool $removeParent = false): void
    {
        Log::info("Removing child relation for person {$personId}, gender: {$gender}, removeParent: {$removeParent}");

        $marriage = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active->value)
            ->first();


        if ($marriage) {
            // Check if person has children
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)
                // ->where('child_id1', $personId)
                ->first();

            Log::info("haschildren {$hasChildren}");

            if ($hasChildren && $removeParent) {
                Log::info("Person {$personId} has children. Removing parent's relation from the child. marriage id is :{$marriage->id} and  child id is : {$hasChildren->child_id}");
                // $personChild = PersonChild::where('person_marriage_id', $marriage->id)
                //     ->where('child_id', $hasChildren->child_id)->first();

                // Log::info("the person child must be delete { $personChild}");

                $hasChildren->delete();
            } elseif (!$hasChildren) {
                Log::info("Person {$personId} has no children. Removing child relation from parents.");
                PersonChild::where('child_id', $personId)->delete();
            }
        } else {
            Log::info("No marriage found for person {$personId}. Removing direct parent relation.");
            PersonChild::where('child_id', $personId)->delete();
        }

        Log::info("Child relation removal completed for person {$personId}");
    }

    /**
     * Remove all spouse relations for a person.
     */
    protected function removeSpouseRelations(int $personId, bool $isMale): void
    {
        $spouseColumn = $isMale == 1 ? 'man_id' : 'woman_id';

        // Find and delete all active marriages
        $marriages = PersonMarriage::where($spouseColumn, $personId)
            ->where('status', Status::Active->value)
            ->get();

        foreach ($marriages as $marriage) {
            Log::info("Removing spouse relation: Marriage ID {$marriage->id} for person {$personId}");
            $marriage->delete();
        }

        Log::info("All spouse relations removed for person {$personId}.");
    }


    /**
     * Returns an error response for GraphQL.
     */
    protected function errorResponse(string $message, int $personId)
    {
        Log::error("Error response triggered for person {$personId}: {$message}");
        return Error::createLocatedError($message);
    }

    protected function updateUserClanId(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) {
            Log::warning("User {$userId} not found while trying to update clan_id.");
            return;
        }

        $newClanId = "clan_" . $userId;
        $user->clan_id = $newClanId;
        $user->save();

        Log::info("Updated user {$userId} clan_id to {$newClanId}.");

        // 🔥 Fire an event to notify about the clan update
        event(new ClanUpdated($userId, $newClanId));
    }
}
