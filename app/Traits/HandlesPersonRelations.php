<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use App\GraphQL\Enums\Status;

trait HandlesPersonRelations
{
    /**
     * Checks if the user is authorized to delete the person.
     */
    protected function isUserAuthorized(int $userId, int $personId): bool
    {
        $clanUserIds = $this->getAllUserIdsSmallClan($personId);
        return empty($clanUserIds) || in_array($userId, $clanUserIds);
    }

    /**
     * Checks if the person's parents each have their own parents (grandparents exist).
     */
    protected function hasGrandparents(array $parentIds): bool
    {
        if (count($parentIds) === 2) {
            return !empty($this->getParentIds($parentIds[0])) && !empty($this->getParentIds($parentIds[1]));
        }
        return false;
    }

    /**
     * Handles the logic for removing child relationships based on conditions.
     */
    protected function handleChildRemoval(int $personId, int $gender, array $childrenIds, array $parentIds): void
    {
        if (count($childrenIds) === 1 && empty($parentIds)) {
            Log::info("Person {$personId} has one child but no parents. Removing child relation...");
            $this->removeChildRelation($personId, $gender, true);
        } else {
            Log::info("Person {$personId} is a child, removing parental relation.");
            $this->removeChildRelation($personId, $gender);
        }
    }

    /**
     * Checks if a person has any remaining relations.
     */
    protected function isPersonIsolated(int $personId): bool
    {
        return empty($this->getParentIds($personId)) &&
               empty($this->getSpouseIds($personId, 1)) && // Male spouses
               empty($this->getSpouseIds($personId, 0)) && // Female spouses
               empty($this->getChildrenIds($this->getSpouseIds($personId, 1))) &&
               empty($this->getChildrenIds($this->getSpouseIds($personId, 0)));
    }
}
