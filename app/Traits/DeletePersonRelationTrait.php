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

    public function deletePersonRelation($personId)
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
                } elseif ($this->hasParentsPersonOrPersonSpouses($person)) {
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
        Log::info("CanDeletePerson: Checking deletion conditions for Person ID {$personId} and gender is : {$gender}");

        // Fetch marriage IDs associated with the person
        $marriageIds = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->pluck('id');
        // ->pluck($gender == 0 ? 'man_id' : 'woman_id');

        Log::info("CanDeletePerson: marriageIds are : " . json_encode($marriageIds));


        $countChildren = PersonChild::whereIn('person_marriage_id', $marriageIds)->count();
        Log::info("CanDeletePerson: Person ID {$personId} has {$countChildren} children.");

        if ($countChildren == 0) {
            // Check for spouse relationships
            $spouseIds = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)
                ->pluck($gender == 0 ? 'man_id' : 'woman_id');

            Log::info("canDeletePerson and  spouseIds" . json_encode($spouseIds) . "and count is:" . count($spouseIds) . "empty check is :" . empty($spouseIds));


            if ((count($spouseIds) == 0)) {
                Log::info("is the person userowner the same :" . $userOwner->id != $personId);

                if ($userOwner->id != $personId) {
                    Log::info("the person {$personId} is no the same with user logged in with id {$userOwner->id}. so must check the person again has parent or not !");

                    if ($this->hasParentsPersonOrPersonSpouses($person)) {
                        return $this->removeParentRelation($personId, $gender, true);
                    }
                    throw new Exception("This person doesn't have any relation yet and you can delete it.");


                } else {
                    Log::info("Person Can be delete and has no relations here.");
                    throw new Exception("This person doesn't have any relation yet and you can delete it.");
                    //return true;
                }
            }

            Log::info("CanDeletePerson: Spouse IDs found for Person ID {$personId}: " . implode(', ', $spouseIds->toArray()));

            if ($this->IsthePersonSpouseOfUserLoggedIn($spouseIds, $userOwner)) {
                Log::info("CanDeletePerson: User is a spouse. Removing marriage.");
                return $this->removeMarriage($personId, $gender);
            } elseif ($this->IsthePersonChildOfUserLoggedIn($person, $userOwner)) {
                Log::info("CanDeletePerson: User is a parent. Removing parent relation.");
                return $this->removeParentRelation($personId, $gender);
            } else {
                Log::info("CanDeletePerson: No direct relation. Removing marriage.");
                return $this->removeMarriage($personId, $gender);
            }
        }

        if ($countChildren > 1) {
            Log::warning("CanDeletePerson: Person ID {$personId} has multiple children and cannot be deleted.");
            throw new Exception("Person has more than one child and cannot be deleted.");
        }

        if ($countChildren == 1 && (!$this->hasParentsPersonOrPersonSpouses($person))) {
            // Log::info("CanDeletePerson: chcking parent of person with the user logged in ".$this->hasParents($personId) );
            Log::info("CanDeletePerson: Removing child relation with parent for Person ID {$personId}");
            return $this->removeChildRelationWithParent($personId, $gender);
        } else {
            throw new Exception("person parents exist and cannot delete it.");

        }


        return false;
    }

    protected function hasParentsPersonOrPersonSpouses($person)
    {
        $hasParentsPersonOrSpouses = false;
        $spouseIds = [];
        $hasParentsPersonOrSpouses = PersonChild::where('child_id', $person->id)->exists();

        Log::info("person Parents is : " . $hasParentsPersonOrSpouses);

        if ($hasParentsPersonOrSpouses) {
            return $hasParentsPersonOrSpouses;
        }


        $spouseIds = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $person->id)
            ->pluck($person->gender == 0 ? 'man_id' : 'woman_id')->toArray();

        Log::info("person spouses hasParents is : " . json_encode($spouseIds));

        //$spouseIds = collect($spouseIds)->pluck('id')->filter()->toArray();

        Log::info("person spouses hasParents array are : " . json_encode($spouseIds));

        if (count($spouseIds) >= 1) { // Check if it's not empty
            // Use whereIn to check multiple spouse IDs
            Log::info("the count of person spouses parents in personchild :" . json_encode(PersonChild::whereIn('child_id', $spouseIds)->pluck('id')));
            $hasParentsPersonOrSpouses = PersonChild::whereIn('child_id', $spouseIds)->exists();


        }

        Log::info("hasParentsPersonOrSpouses fianl result is : " . $hasParentsPersonOrSpouses);

        return $hasParentsPersonOrSpouses;
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

    protected function removeParentRelation($personId, $gender, $downside = false)
    {
        Log::info("RemoveParentRelation: Attempting to remove parent relation for Person ID {$personId}");

        if (!$downside) {
            Log::info("RemoveParentRelation: the person {$personId} is a child  and must remove from downside ");

            $parentRecord = PersonChild::where('child_id', $personId)->first();

            if (!$parentRecord) {
                Log::info("RemoveParentRelation: No parent record found for Person ID {$personId}");
                return false;
            }

            Log::info("RemoveParentRelation: Successfully removed parent-child relation for Person ID {$personId}");
            $parentRecord->delete();
            return true;

        } else {
            Log::info("RemoveParentRelation: the person {$personId} is one of parent and remove from upside ");
            $marriages = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)->get();


            foreach ($marriages as $marriage) {
                $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->first();
                if ($hasChildren) {
                    Log::info("RemoveParentRelation: Removing parent marriage relation with child for Person ID {$personId}");
                    $hasChildren->delete();
                    return true;
                } else {
                    Log::warning("RemoveParentRelation: no children found  for Person ID {$personId} .");
                }
            }
        }
        return false;
    }

    protected function removeChildRelationWithParent($personId, $gender)
    {
        Log::info("RemoveChildRelationWithParent: Removing parent-child relation for Person ID {$personId}");
        return $this->removeParentRelation($personId, $gender, true);
    }

    protected function isThePersonOwnerUser($person, $userOwner)
    {

        return ($person->id == $userOwner->id);
    }
}
