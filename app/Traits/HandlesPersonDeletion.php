<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

trait HandlesPersonDeletion
{
    use SmallClanTrait;

    public function deletePerson($personId)
    {
        DB::beginTransaction();
        try {
            Log::info("Starting deletion process for Person ID: {$personId}");

            $person = Person::find($personId);
            if (!$person || $person->status !== Status::Active->value) {
                Log::error("Person ID: {$personId} not found or inactive.");
                throw new \Exception("Person not found or inactive.");
            }

            $this->validateSmallClanConstraints($person);

            if ($this->hasChildren($personId)) {
                Log::info("Person ID: {$personId} has children. Removing children first.");
                if (!$this->removeChildren($personId)) {
                    throw new \Exception("Failed to remove children for Person ID: {$personId}");
                }
            }

            if ($this->hasParents($personId)) {
                Log::info("Person ID: {$personId} has parents. Removing parent relation.");
                if (!$this->removeParentRelation($personId)) {
                    throw new \Exception("Failed to remove parent relation for Person ID: {$personId}");
                }
            }

            if ($this->hasSpouses($personId)) {
                Log::info("Person ID: {$personId} has spouses. Removing spouse relation.");
                if (!$this->removeSpouseRelation($personId)) {
                    throw new \Exception("Failed to remove spouse relation for Person ID: {$personId}");
                }
            }

            Log::info("Person ID: {$personId} has no remaining relations. Deleting person.");
            if (!$this->deletePersonNode($personId)) {
                throw new \Exception("Failed to delete Person ID: {$personId}");
            }

            DB::commit();
            Log::info("Successfully deleted Person ID: {$personId}");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting Person ID: {$personId} - " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /** ==================== Small Clan Validation ==================== */
    private function validateSmallClanConstraints($person)
    {
        $userId = $this->getUserId();
        $smallClanUsers = $this->getAllUserIdsSmallClan($person->id);

        Log::info("Validating small clan constraints for Person ID: {$person->id}");

        if (!in_array($userId, $smallClanUsers) && !empty($smallClanUsers)) {
            Log::error("User ID: {$userId} is not authorized to delete Person ID: {$person->id}");
            throw new \Exception("You are not authorized to delete this person.");
        }

        if ($person->is_owner) {
            if ($this->isSingleOwner($person->id) && $person->creator_id !== $userId) {
                Log::error("Only the creator can remove the sole owner (Person ID: {$person->id}).");
                throw new \Exception("Only the creator can remove the sole owner.");
            }
        }

        Log::info("Small clan validation passed for Person ID: {$person->id}");
    }

    /** ==================== Children Removal ==================== */
    private function hasChildren($personId)
    {
        $person = Person::find($personId);
        if (!$person) return false;

        $personMarriageIds = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck('id');

        return PersonChild::whereIn('person_marriage_id', $personMarriageIds)->exists();
    }

    private function removeChildren($personId)
    {
        Log::info("Removing children for Person ID: {$personId}");

        $person = Person::find($personId);
        if (!$person) return false;

        $personMarriageIds = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck('id');

        if ($personMarriageIds->isEmpty()) return false;

        PersonChild::whereIn('person_marriage_id', $personMarriageIds)->delete();
        Log::info("Removed children relations for Person ID: {$personId}");
        return true;
    }

    /** ==================== Parent Removal ==================== */
    private function hasParents($personId)
    {
        return PersonChild::where('child_id', $personId)->exists();
    }

    private function removeParentRelation($personId)
    {
        Log::info("Removing parent relation for Person ID: {$personId}");

        $parentRelation = PersonChild::where('child_id', $personId)->first();
        if (!$parentRelation) return false;

        $personMarriage = PersonMarriage::where('id', $parentRelation->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        if (!$personMarriage) return false;

        $childCount = PersonChild::where('person_marriage_id', $personMarriage->id)->count();

        if ($childCount === 1) {
            $parentRelation->delete();
            Log::info("Removed parent relation for Person ID: {$personId}");
            return true;
        }

        Log::warning("Person ID: {$personId} has multiple children. Cannot remove parent relation.");
        return false;
    }

    /** ==================== Spouse Removal ==================== */
    private function hasSpouses($personId)
    {
        $person = Person::find($personId);
        if (!$person) return false;

        return PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->exists();
    }

    private function removeSpouseRelation($personId)
    {
        Log::info("Removing spouse relation for Person ID: {$personId}");

        $person = Person::find($personId);
        if (!$person) return false;

        $spouseRelations = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->get();

        foreach ($spouseRelations as $relation) {
            if (!$this->hasChildrenFromMarriage($relation->id)) {
                $relation->delete();
                Log::info("Removed spouse relation for Person ID: {$personId}");
                return true;
            }
        }

        Log::warning("Person ID: {$personId} has children. Cannot remove spouse relation.");
        return false;
    }

    private function hasChildrenFromMarriage($marriageId)
    {
        return PersonChild::where('person_marriage_id', $marriageId)->exists();
    }

    /** ==================== Final Node Deletion ==================== */
    private function deletePersonNode($personId)
    {
        Log::info("Deleting Person ID: {$personId}");

        $person = Person::find($personId);
        if (!$person) return false;

        $person->delete();
        $this->updateUserClanId($person->creator_id);
        Log::info("Deleted Person ID: {$personId} and updated clan.");
        return true;
    }

    private function updateUserClanId($userId)
    {
        DB::table('users')->where('id', $userId)->update(['clan_id' => $userId]);
        Log::info("Updated clan ID for User ID: {$userId}");
    }
}
