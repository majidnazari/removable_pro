<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use App\GraphQL\Enums\Status;

trait HandlesPersonDeletion_old2
{
    use FindOwnerTrait;

    /**
     * Checks if a person can be deleted by ensuring all related entities can be deleted.
     */
    public function canDeletePerson($userId, $personId, &$checkedIds = [])
    {
        //       Log::info("Checking if user {$userId} can delete person {$personId}. Checked IDs: " . json_encode($checkedIds));

        if (in_array($personId, $checkedIds)) {
            Log::warning("Preventing infinite loop for person {$personId}");
            //           Log::info("Returning TRUE for person {$personId} to prevent recursion.");
            return true;
        }

        $checkedIds[] = $personId;
        $person = Person::find($personId);

        if (!$person) {
            Log::error("Person ID {$personId} not found.");
            return $this->errorResponse("Person-DELETE-PERSON_NOT_FOUND", $personId);
        }

        if ($person->is_owner) {
            Log::error("Person ID {$personId} is an owner and cannot be deleted.");
            return $this->errorResponse("Person-DELETE-CANNOT_DELETE_OWNER", $personId);
        }

        // Get relationships
        $parentIds = $this->getParentIds($personId);
        $spouseIds = $this->getSpouseIds($personId);
        $childrenIds = $this->getChildrenIds([$personId]);

        //  Special condition: Allow deletion if person has one child & their parent has no parent
        if (count($childrenIds) === 1 && !empty($parentIds)) {
            foreach ($parentIds as $parentId) {
                $grandParentIds = $this->getParentIds($parentId);
                if (empty($grandParentIds)) {
                    return true;
                }
            }
        }

        // Recursively check if children can be deleted
        foreach ($childrenIds as $childId) {
            if (!$this->canDeletePerson($userId, $childId, $checkedIds)) {
                Log::error("Cannot delete child {$childId}. Blocking deletion of person {$personId}.");
                return $this->errorResponse("Person-DELETE-CANNOT_DELETE_CHILD", $childId);
            }
        }

        // Recursively check if spouses can be deleted
        foreach ($spouseIds as $spouseId) {
            if (!$this->canDeletePerson($userId, $spouseId, $checkedIds)) {
                Log::error("Cannot delete spouse {$spouseId}. Blocking deletion of person {$personId}.");
                return $this->errorResponse("Person-DELETE-CANNOT_DELETE_SPOUSE", $spouseId);
            }
        }

        // Recursively check if parents can be deleted
        foreach ($parentIds as $parentId) {
            if (!$this->canDeletePerson($userId, $parentId, $checkedIds)) {
                Log::error("Cannot delete parent {$parentId}. Blocking deletion of person {$personId}.");
                return $this->errorResponse("Person-DELETE-CANNOT_DELETE_PARENT", $parentId);
            }
        }

        return true;
    }


    /**
     * Get parent IDs of a person from the PersonChild table
     */
    protected function getParentIds($personId)
    {
        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation || !$parentRelation->person_marriage_id) {
            return [];
        }

        $parentMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        $parents = $parentMarriage ? array_filter([$parentMarriage->man_id, $parentMarriage->woman_id]) : [];

        return $parents;
    }

    /**
     * Get all spouses of a person from the PersonMarriage table
     */
    protected function getSpouseIds($personId)
    {
        $spouses = PersonMarriage::where(function ($query) use ($personId) {
            $query->where('man_id', $personId)
                ->orWhere('woman_id', $personId);
        })
            ->where('status', Status::Active)
            ->pluck('man_id', 'woman_id')
            ->flatten()
            ->reject(fn($id) => $id == $personId) // Remove self-references
            ->values()
            ->toArray();

        return $spouses;
    }

    /**
     * Get all children of a given set of parent IDs
     */
    protected function getChildrenIds(array $parentIds)
    {
        if (empty($parentIds)) {
            return [];
        }

        $children = PersonChild::whereIn('person_marriage_id', function ($query) use ($parentIds) {
            $query->select('id')->from('person_marriages')
                ->whereIn('man_id', $parentIds)
                ->orWhereIn('woman_id', $parentIds);
        })->pluck('child_id')->toArray();

        return $children;
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
