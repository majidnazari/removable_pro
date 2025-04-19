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

trait HandlesPersonDeletion_old3
{
    use FindOwnerTrait;
    use SmallClanTrait;

    public function DeletePersonRelation(int $userId, int $personId, array &$checkedIds = []): bool|Error
    {
        if (in_array($personId, $checkedIds)) {
            Log::warning("Skipping already checked person {$personId} to prevent infinite loop.");
            return true;
        }

//       Log::info("Checking deletion for person {$personId}...");
        $checkedIds[] = $personId;

        $person = Person::find($personId);
        if (!$person) {
            return $this->errorResponse("Person-DELETE-RELATION-PERSON_NOT_FOUND", $personId);
        }

        $clanUserIds = $this->getAllUserIdsSmallClan($personId);
        if (!empty($clanUserIds) && !in_array($userId, $clanUserIds)) {
            return $this->errorResponse("Person-DELETE-RELATION-NOT_AUTHORIZED", $personId);
        }

        if ($person->is_owner && count($this->getAllOwnerIdsSmallClan($personId)) > 1) {
            return $this->errorResponse("Person-DELETE-RELATION-CANNOT_DELETE_OWNER", $personId);
        }

        $parentIds = $this->getParentIds($personId);
        $isThereAnyOwnerInSpouseIds = $this->isThereOwnerInSpouses($personId, $person->gender);
        $isThereAnyOwnerInParentIds = $this->isThereOwnerInParent($personId, $person->gender);
        $marriageIds = $this->getSpouseIds($personId, $person->gender);
        $childrenIds = $this->getChildrenIds($marriageIds);

        if (count($parentIds) === 2) {
            $fatherParents = $this->getParentIds($parentIds[0]);
            $motherParents = $this->getParentIds($parentIds[1]);

            if (!empty($fatherParents) && !empty($motherParents)) {
                return $this->errorResponse("Person-DELETE-RELATION-PARENT_HAS_PARENTS", $personId);
            }
        }

        if ($person->is_owner && !empty($parentIds)) {
            return $this->errorResponse("Person-DELETE-RELATION-OWNER_MUST_DELETE_PARENTS", $personId);
        }

        if (count($childrenIds) > 1) {
            return $this->errorResponse("Person-DELETE-RELATION-HAS_MULTIPLE_CHILDREN", $personId);
        }

        if (count($childrenIds) === 1 && empty($parentIds)) {
//           Log::info("Person {$personId} has one child but no parents. Removing child relation...");
            $this->removeParentRelationWithChild($personId, $person->gender);
        } else if (empty($isThereAnyOwnerInParentIds) || in_array($userId, $isThereAnyOwnerInParentIds)) {
//           Log::info("Person {$personId} is a child, removing parental relation.");
            $this->removeChildRelationWithParent($personId, $person->gender);
        }


        if (!$isThereAnyOwnerInSpouseIds) {
            $this->removeSpouseRelations($personId, $person->gender);
        }


        if (!$isThereAnyOwnerInParentIds) {
            $this->removeParentRelations($personId, $person->gender);
        }
        // 🆕 Check if person has any remaining relations after removal
        $remainingParentIds = $this->getParentIds($personId);
        $remainingSpouseIds = $this->getSpouseIds($personId, $person->gender);
        $remainingChildrenIds = $this->getChildrenIds($remainingSpouseIds);

//       Log::info("remainingParentIds {" . json_encode($remainingParentIds) . "} is remainingSpouseIds {" . json_encode($remainingSpouseIds) . "} and the remainingChildrenIds [" . json_encode($remainingChildrenIds) . "}");


        if (empty($remainingParentIds) && empty($remainingSpouseIds) && empty($remainingChildrenIds) && ($person->is_owner == 1) && ($person->creator_id == $userId)) {
//           Log::info("Person {$personId} has no remaining relations. Updating clan_id...");
            $this->updateUserClanId($userId);
        }

//       Log::info("Person {$personId} can be deleted.");
        return true;
    }


    /**
     * Get parent IDs of a person from the PersonChild table.
     */
    protected function getParentIds(int $personId): array
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
//           Log::info("No parents found for person {$personId}.");
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

//       Log::info("Spouse IDs for person {$personId}: " . json_encode($spouseIds));
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

//       Log::info("Children IDs retrieved: " . json_encode($childrenIds));
        return $childrenIds;
    }

    /**
     * Check if there is an owner among all spouses.
     */
    protected function isThereOwnerInSpouses(int $personId, bool $isMale): array
    {
        $ownerSpouseIds=[];

        $spouseIds = PersonMarriage::where($isMale == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck($isMale == 1 ? 'woman_id' : 'man_id')
            ->toArray();

//       Log::info("isThereOwnerInSpouses of person {$personId}  are :" . json_encode($spouseIds));
        if (empty($spouseIds)) {
            return $ownerSpouseIds;
        }

        $ownerSpouseIds = Person::whereIn('id', $spouseIds)
            ->where('status', Status::Active)
            ->where('is_owner', true)
            ->pluck('id')
            ->toArray();

        return $ownerSpouseIds;
    }

    protected function isThereOwnerInParent(int $personId, bool $isMale): array
    {
        $ownerPresentIds=[];
        $personChild = PersonChild::where('child_id', $personId)
            ->where('status', Status::Active)
            ->pluck('person_marriage_id')
            ->first();

//       Log::info("isThereOwnerInParent of person {$personId}  are :" . json_encode($personChild));
        if (empty($personChild)) {
            return $ownerPresentIds;
        }

        $personMarriage = PersonMarriage::where('id', $personChild->person_marriage_id)
           // ->where(column: 'status', Status::Active->value)
            ->first();

        if (empty($personMarriage)) {
            return $ownerPresentIds;
        }
        $ownerPresentIds = Person::whereIn('id', [$personMarriage->man_id, $personMarriage->woman_id])
            ->where('status', Status::Active)
            ->where('is_owner', true)
            ->pluck('id')
            ->toArray();

        return  $ownerPresentIds;

    }

    /**
     * Remove parent-child relationship.
     */
    protected function removeChildRelationWithParent(int $personId, int $gender): void
    {
//       Log::info("removeChildRelationWithParent person {$personId}, gender: {$gender}");
        PersonChild::where('child_id', $personId)->delete();
//       Log::info("Child relation removal completed for person {$personId}");
    }

    protected function removeParentRelationWithChild(int $personId, int $gender): void
    {
        $marriage = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active->value)
            ->first();

        if ($marriage) {
            // Check if person has children
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)
                ->first();

//           Log::info("haschildren {$hasChildren}");

            if ($hasChildren) {
//               Log::info("Person {$personId} has children. Removing parent's relation from the child. marriage id is :{$marriage->id} and  child id is : {$hasChildren->child_id}");

                $hasChildren->delete();
            } elseif (!$hasChildren) {
//               Log::info("Person {$personId} has no children. Removing child relation from parents.");
                PersonChild::where('child_id', $personId)->delete();
            }
        }
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
//           Log::info("Removing spouse relation: Marriage ID {$marriage->id} for person {$personId}");
            $marriage->delete();
        }

//       Log::info("All spouse relations removed for person {$personId}.");
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

//       Log::info("Updated user {$userId} clan_id to {$newClanId}.");

        // 🔥 Fire an event to notify about the clan update
        event(new ClanUpdated($userId, $newClanId));
    }
}
