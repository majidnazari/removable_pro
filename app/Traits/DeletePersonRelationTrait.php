<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

trait DeletePersonRelationTrait
{
    use SmallClanTrait;
    use FindOwnerTrait;

    public function deletePerson($personId)
    {
        DB::beginTransaction();
        try {
            $userId = auth()->id();
            $userOwner = $this->findOwner();
            Log::info("DeletePerson: User {$userId} attempting to delete Person ID {$personId}");

            $person = Person::where('id', $personId)->where('status', Status::Active)->first();
            if (!$person) {
                Log::warning("DeletePerson: Person ID {$personId} not found or inactive.");
                throw new Exception("Person not found.");
            }

            // 1. Check if user is part of the small clan
            $smallClanIds = $this->getAllUserIdsSmallClan($personId);
            Log::info("DeletePerson: Small Clan Members: " . implode(', ', $smallClanIds));
            if (!in_array($userId, $smallClanIds) && !empty($smallClanIds)) {
                Log::warning("DeletePerson: User {$userId} is not in the small clan.");
                throw new Exception("You are not allowed to delete this person.");
            }

            // 2. Special deletion rules for owners
            if ($person->is_owner) {
                $personId = $person->id;
                Log::info("DeletePerson: Checking if User {$userId} is the creator of Owner ID {$personId}");

                if ($userOwner->id != $personId) {
                    Log::warning("DeletePerson: User {$userOwner->id} is not the creator of the sole owner.");
                    throw new Exception("Only the creator can delete the sole owner.");
                } elseif ($this->hasParents($personId)) {
                    Log::warning("DeletePerson: Person ID {$personId} has parents and cannot be deleted.");
                    throw new Exception("The person has parents and cannot be deleted.");
                }
            }

            // 3. Check deletion conditions
            Log::info("DeletePerson: Checking if Person ID {$personId} can be deleted.");
            if (!$this->canDeletePerson($person, $userOwner)) {
                Log::warning("DeletePerson: Person ID {$personId} cannot be deleted due to existing relationships.");
                throw new Exception("Person cannot be deleted due to existing relationships.");
            }

            // 4. Perform deletion
            //Log::info("DeletePerson: Deleting Person ID {$personId}");
            //Person::where('id', $personId)->delete();
            DB::commit();

            //Log::info("DeletePerson: Successfully deleted Person ID {$personId}");
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("DeletePerson Error: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    protected function canDeletePerson($person, $userOwner)
    {
        $spouseIds = [];
        $personId = $person->id;
        $gender = $person->gender;
        Log::info("CanDeletePerson: Checking deletion conditions for Person ID {$personId}");

        // Fetch marriage IDs associated with the person
        $marriageIds = PersonMarriage::where($gender == 0 ? 'man_id' : 'woman_id', $personId)->pluck('id');
        $countChildren = PersonChild::whereIn('person_marriage_id', $marriageIds)->count();
        Log::info("CanDeletePerson: Person ID {$personId} has {$countChildren} children.");

        if ($countChildren == 0) {
            // Check for spouse relationships
            $spouseIds = PersonMarriage::where($gender == 0 ? 'man_id' : 'woman_id', $personId)
                ->pluck($gender == 0 ? 'man_id' : 'woman_id');

            Log::info("canDeletePerson and  spouseIds" . json_encode($spouseIds) . "and count is:" . count($spouseIds) . "empty check is :" . empty($spouseIds));


            if ((count($spouseIds) == 0)) {
                Log::info("is the person userowner the same :" . $userOwner->id != $personId);

                if ($userOwner->id != $personId) {
                    return $this->removeParentRelation($personId);

                } else {
                    Log::info("Person Can be delete and has no relations here.");

                }
            }

            Log::info("CanDeletePerson: Spouse IDs found for Person ID {$personId}: " . implode(', ', $spouseIds->toArray()));

            if ($this->IsthePersonSpouseOfUserLoggedIn($spouseIds, $userOwner)) {
                Log::info("CanDeletePerson: User is a spouse. Removing marriage.");
                return $this->removeMarriage($personId, $gender);
            } elseif ($this->IsthePersonChildOfUserLoggedIn($person, $userOwner)) {
                Log::info("CanDeletePerson: User is a parent. Removing parent relation.");
                return $this->removeParentRelation($personId);
            } else {
                Log::info("CanDeletePerson: No direct relation. Removing marriage.");
                return $this->removeMarriage($personId, $gender);
            }
        }

        if ($countChildren == 1 && !$this->hasParents($personId)) {
            Log::info("CanDeletePerson: Removing child relation with parent for Person ID {$personId}");
            return $this->removeChildRelationWithParent($personId);
        }

        if ($countChildren > 1) {
            Log::warning("CanDeletePerson: Person ID {$personId} has multiple children and cannot be deleted.");
            throw new Exception("Person has more than one child and cannot be deleted.");
        }

        return false;
    }

    protected function hasParents($personId)
    {
        return PersonChild::where('child_id', $personId)->exists();
    }

    protected function IsthePersonSpouseOfUserLoggedIn($spouseIds, $userOwner)
    {
        // Ensure $spouseIds is a collection or array
        if (!$spouseIds || empty($spouseIds)) {
            Log::info("IsthePersonSpouseUserLoggedIn: No spouses found. Returning FALSE.");
            return false;
        }

        // If it's a single object, wrap it in an array
        if (!is_iterable($spouseIds)) {
            $spouseIds = [$spouseIds];
        }

        // Extract IDs properly
        $spouseArray = collect($spouseIds)->pluck('id')->filter()->toArray();

        // Debugging logs
        Log::info("IsthePersonSpouseUserLoggedIn: Extracted Spouse IDs => " . json_encode($spouseArray));
        Log::info("IsthePersonSpouseUserLoggedIn: Checking if User ID " . json_encode($userOwner) . " is in Spouse List.");
        Log::info("IsthePersonSpouseUserLoggedIn: result : " . in_array($userOwner->id, $spouseArray));

        return in_array($userOwner->id, $spouseArray);
    }



    protected function IsthePersonChildOfUserLoggedIn($person, $userOwner)
    {
        Log::info("Checking if User {$userOwner->id} is a parent of Person ID {$person->id}");

        $parentIds = PersonChild::where('child_id', $person->id)
            ->join('person_marriages', 'person_children.person_marriage_id', '=', 'person_marriages.id')
            ->select('person_marriages.man_id', 'person_marriages.woman_id')
            ->get();

        foreach ($parentIds as $parent) {
            if ($parent->man_id == $userOwner->id || $parent->woman_id == $userOwner->id) {
                Log::info("User {$userOwner->id} is a parent of Person ID {$person->id}");
                return true;
            }
        }

        Log::info("User {$userOwner->id} is not a parent of Person ID {$person->id}");
        return false;
    }

    protected function removeMarriage($personId, $gender)
    {
        Log::info("RemoveMarriage: Attempting to remove marriage for Person ID {$personId}");
        $marriages = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)->get();

        foreach ($marriages as $marriage) {
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->exists();
            if (!$hasChildren) {
                Log::info("RemoveMarriage: Removing marriage for Person ID {$personId}");
                $marriage->delete();
                return true;
            } else {
                Log::warning("RemoveMarriage: Cannot remove marriage for Person ID {$personId} because spouse has children.");
            }
        }
        return false;
    }

    protected function removeParentRelation($personId)
    {
        Log::info("RemoveParentRelation: Attempting to remove parent relation for Person ID {$personId}");
        $parentRecord = PersonChild::where('child_id', $personId)->first();

        if (!$parentRecord) {
            Log::info("RemoveParentRelation: No parent record found for Person ID {$personId}");
            return false;
        }

        Log::info("RemoveParentRelation: Successfully removed parent-child relation for Person ID {$personId}");
        $parentRecord->delete();
        return true;
    }

    protected function removeChildRelationWithParent($personId)
    {
        Log::info("RemoveChildRelationWithParent: Removing parent-child relation for Person ID {$personId}");
        return $this->removeParentRelation($personId);
    }

    protected function isThePersonOwnerUser($person, $userOwner)
    {

        return ($person->id == $userOwner->id);
    }
}
