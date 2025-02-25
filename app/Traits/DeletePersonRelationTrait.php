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
            Log::info("DeletePerson: Checking deletion for Person ID {$personId} by User ID {$userId}");

            $person = Person::where('id', $personId)->where('status', Status::Active)->first();
            if (!$person) {
                throw new Exception("Person not found.");
            }

            // 1. Check if user is part of the small clan
            $smallClanIds = $this->getAllUserIdsSmallClan($personId);
            if (!in_array($userId, $smallClanIds) && !empty($smallClanIds)) {
                throw new Exception("You are not allowed to delete this person.");
            }

            // 2. Handle special deletion rules for owners
            if ($person->is_owner) {
                if (count($smallClanIds) == 1) {
                    $creatorId = Person::where('id', $personId)->value('creator_id');
                    if ($userId != $creatorId) {
                        throw new Exception("Only the creator can delete the sole owner.");
                    }
                }
            }

            // 3. Check step-by-step deletion conditions
            if (!$this->canDeletePerson($person, $userOwner)) {
                throw new Exception("Person cannot be deleted due to existing relationships.");
            }

            // 4. Perform deletion
            Log::info("Deleting person ID: {$personId}");
            Person::where('id', $personId)->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("DeletePerson Error: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    protected function canDeletePerson($person, $userOwner)
    {
        $personId = $person->id;

        // 1. If the person has children, they cannot be deleted
        $marriageIds = PersonMarriage::where($person->gender == 0 ? 'man_id' : 'woman_id', $personId)->pluck('id');
        //$hasChildren = PersonChild::whereIn('person_marriage_id', $marriageIds)->exists();
        $countChildren = PersonChild::whereIn('person_marriage_id', $marriageIds)->count();

        if ($countChildren == 0) {
            $theUserInPersonSpouse = PersonMarriage::where($person->gender == 0 ? 'man_id' : 'woman_id', $personId)->where($person->gender == 1 ? 'man_id' : 'woman_id', $userOwner->id)->exists();
            $personIsUserChild = $this->findChildren($userOwner, $person);
            if ($theUserInPersonSpouse) {
                return $this->removeMarriage($personId, $person->gender);
            } else if ((!$theUserInPersonSpouse) && (!$personIsUserChild)) {

                return $this->removeMarriage($personId, $person->gender);
            } else if ((!$theUserInPersonSpouse) && ($personIsUserChild)) {

                return $this->removeParentRelation($personId);


            }
        } elseif ($countChildren == 1) {
            $userParentIds = $this->findParent($userOwner);
            if ($userParentIds) {
                throw new Exception("Person cannot be deleted due to existing relationships with parents.");

            } else {
                $this->removeChildRelationWithParent($person->id, $person->gender);
            }
            //$userParentIds=$this->findParent($userOwner);

        } elseif ($countChildren > 1) {
            throw new Exception("there are more than one children!");

        } else {
            if ($userOwner->id == $personId) {
                Log::error("DeletePerson it is single relation and node can be deleted");

            } else {
                return $this->removeParentRelation($personId);
            }
        }
        // if ($hasChildren) {
        //     Log::info("Person ID {$personId} cannot be deleted: has children.");
        //     return false;
        // }

        // // 2. If the person has spouses, only remove marriage, not the person
        // $hasSpouse = PersonMarriage::where($person->gender == 0 ? 'man_id' : 'woman_id', $personId)->exists();
        // if ($hasSpouse) {
        //     Log::info("Person ID {$personId} has a spouse, checking marriage relation.");
        //     return $this->removeMarriage($personId, $person->gender);
        // }

        // // 3. If the person has parents, ensure they have only one child before deletion
        // $hasParents = $this->hasParents($personId);
        // if ($hasParents) {
        //     Log::info("Person ID {$personId} has parents, checking child count.");
        //     return $this->removeParentRelation($personId);
        // }

        return true;
    }

    protected function removeMarriage($personId, $gender)
    {
        $marriages = PersonMarriage::where($gender == 1 ? 'man_id' : 'woman_id', $personId)->get();
        foreach ($marriages as $marriage) {
            $spouseId = ($marriage->man_id == $personId) ? $marriage->woman_id : $marriage->man_id;
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->exists();

            if (!$hasChildren) {
                Log::info("Removing marriage for Person ID: {$personId}");
                $marriage->delete();
                return true;
            } else {
                Log::info("Cannot remove marriage for Person ID {$personId}: spouse has children.");
                return false;
            }
        }
        return false;
    }

    protected function removeParentRelation($personId)
    {
        $parentRecord = PersonChild::where('child_id', $personId)->first();
        if (!$parentRecord) {
            Log::info("No parent record found for Person ID {$personId}");
            return false;
        }

        $parentMarriage = PersonMarriage::where('id', $parentRecord->person_marriage_id)->first();
        if (!$parentMarriage) {
            Log::info("No marriage record found for parent of Person ID {$personId}");
            return false;
        }

        $childCount = PersonChild::where('person_marriage_id', $parentRecord->person_marriage_id)->count();
        if ($childCount > 1) {
            Log::info("Cannot remove parent relation for Person ID {$personId}: parent has multiple children.");
            return false;
        }

        Log::info("Removing parent-child relation for Person ID {$personId}");
        $parentRecord->delete();
        return true;
    }

    protected function removeChildRelationWithParent(int $personId, int $gender): void
    {
        Log::info("removeChildRelationWithParent person {$personId}, gender: {$gender}");
        PersonChild::where('child_id', $personId)->delete();
        Log::info("Child relation removal completed for person {$personId}");
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

            Log::info("haschildren {$hasChildren}");

            if ($hasChildren) {
                Log::info("Person {$personId} has children. Removing parent's relation from the child. marriage id is :{$marriage->id} and  child id is : {$hasChildren->child_id}");

                $hasChildren->delete();
            } elseif (!$hasChildren) {
                Log::info("Person {$personId} has no children. Removing child relation from parents.");
                PersonChild::where('child_id', $personId)->delete();
            }
        }
    }

    protected function hasParents($personId)
    {
        return PersonChild::where('child_id', $personId)->exists();
    }

    protected function findChildren($userOwner, $person)
    {

        //$userOwner = $this->findOwner($userId);

        $marriages = PersonMarriage::where($userOwner->gender == 1 ? 'man_id' : 'woman_id', $userOwner->id)->get();
        foreach ($marriages as $marriage) {

            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->where('child_id', $person->id)->exists();
            if ($hasChildren) {
                return true;
            }
        }

        return false;
    }
    protected function findParent($person)
    {
        $parentsIds = [];
        //$userOwner = $this->findOwner($userId);


        $PersonChild = PersonChild::where('child_id', $person->id)->first();
        if ($PersonChild) {
            $parentsIds = PersonMarriage::where('id', $PersonChild->person_marriage_id)->pluck('man_id', 'woman_id')->first();
        }

        return $parentsIds;
    }
}
