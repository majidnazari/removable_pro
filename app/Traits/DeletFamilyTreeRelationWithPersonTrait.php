<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\GetAllBloodPersonsWithSpousesInClanFromHeads;
use App\Traits\GetAllUsersRelationInClanFromHeads;
use App\Traits\AuthUserTrait;
use App\Traits\UpdateUserFlagTrait;
use App\Exceptions\CustomValidationException;

use Exception;

trait DeletFamilyTreeRelationWithPersonTrait
{
    use SmallClanTrait;
    use FindOwnerTrait;
    use GetAllBloodPersonsWithSpousesInClanFromHeads;
    use GetAllUsersRelationInClanFromHeads;
    use AuthUserTrait;
    use UpdateUserFlagTrait;
    private $thereIsOwner = false;
    protected $message = "";
    protected $endUserMessage = "";
    protected $statusCode = 0;

    public function deleteFamilyTreeRelationWithPerson($personId)
    {

        DB::beginTransaction();
        //try {
        $userId = $this->getUserId();//auth()->id();
        $userOwner = $this->findOwner();
        Log::info("DeletePerson: User {$userId} attempting to delete Person ID {$personId}");

        $person = Person::where('id', $personId)->where('status', Status::Active)->first();
        if (!$person) {
            Log::warning("DeletePerson: Person ID {$personId} not found or inactive.");
            //throw new Exception("Person not found.");
            $this->handleError("Person not found.", "شخص مورد نظر پیدا نشد", 400);


        }

        $heads = $this->getAllBloodPersonsWithSpousesInClanFromHeads($userId);
        if (!in_array($personId, $heads)) {
            Log::warning("getAllBloodPersonsWithSpousesInClanFromHeads:  {$personId} is not in the big clan.");
            //throw new Exception("This person is not in your clan!.");
            $this->handleError("This person is not in your clan!", "این شخص در خانواده شما نیست!", 403);
        }

        // 1. Check if user is part of the small clan
        $smallClanIds = $this->getAllUserIdsSmallClan($personId);
        Log::info("DeletePerson: Small Clan Members: " . implode(', ', $smallClanIds));
        if (!in_array($userId, $smallClanIds) && !empty($smallClanIds)) {
            Log::warning("DeletePerson: User {$userId} is not in the small clan.");
            //throw new Exception("You are not allowed to delete this person.");
            $this->handleError("You are not allowed to delete this person.", "شما اجازه حذف این شخص را ندارید.", 403);
        }

        // 2. Special deletion rules for owners
        if ($person->is_owner) {
            $personId = $person->id;
            Log::info("DeletePerson: Checking if User {$userId} is the creator of Owner ID {$personId}");

            if ($userOwner->id != $personId) {
                Log::warning("DeletePerson: User {$userOwner->id} is not the creator of the sole owner.");
                //throw new Exception("Only the creator can delete the sole owner.");
                $this->handleError("Only the creator can delete the sole owner.", "فقط سازنده مالک میتواند آن را حذف کند.", 403);
            } elseif ($this->hasParentsPersonOrPersonSpouses($person)) {
                Log::warning("DeletePerson: Person ID {$personId} has parents and cannot be deleted.");
                //throw new Exception("The person has parents and cannot be deleted.");
                $this->handleError("The person has parents and cannot be deleted.", "این شخص والدین دارد و نمی توان آن را حذف کرد.", 403);
            }
        }

        // 3. Check deletion conditions
        Log::info("DeletePerson: Checking if Person ID {$personId} can be deleted.");
        if (!$this->canDeletePerson($person, $userOwner)) {
            Log::warning("DeletePerson: Person ID {$personId} cannot be deleted due to existing relationships.");
            //throw new Exception("Person cannot be deleted due to existing relationships.");

            $this->handleError("Person cannot be deleted due to existing relationships.", "شخص به دلیل روابط موجود قابل حذف نیست.", 403);
        }


        $this->thereIsOwner = $this->personSpousesOwner($person);
        Log::warning("hereIsOwner is:" . $this->thereIsOwner);

        // 4. Perform deletion
        //Log::info("DeletePerson: Deleting Person ID {$personId}");
        //Person::where('id', $personId)->delete();
        DB::commit();


        if ($person->is_owner || $this->thereIsOwner) {
            Log::warning("DeletFamilyTreeRelationWithPersonTrait updateUserCalculationFlag into false.");

            $this->updateUserCalculationFlag($userId, false);

            // Ensure the method from another trait is called
            if (method_exists($this, 'getAllUsersInClanFromHeads')) {
                $allUsers = $this->getAllUsersInClanFromHeads($userId);
                Log::info("The result of getAllUsersInClanFromHeads in getUser: " . json_encode($allUsers));
            } else {
                Log::warning("The method getAllUsersInClanFromHeads does not exist in this class.");
            }
        }


        //Log::info("DeletePerson: Successfully deleted Person ID {$personId}");
        return true;
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::error("DeletePerson Error: " . $e->getMessage());
        //     return $e->getMessage();
        // }
    }

    protected function canDeletePerson($person, $userOwner): bool
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
                    Log::info("the person {$personId} is not the same with user logged in with id {$userOwner->id}. so must check the person again has parent or not !");

                    if ($this->hasParentsPersonOrPersonSpouses($person)) {
                        return $this->removeParentRelation($personId, $gender);
                    }
                    //throw new Exception("This person doesn't have any relation yet and you can delete it.");
                    $this->handleError("This person doesn't have any relation yet and you can delete it.", "این شخص  هیچ رابطه ای ندارد و شما می توانید آن را حذف کنید.", 403);



                } else {
                    Log::info("Person Can be delete and has no relations here.");
                    //throw new Exception("This person doesn't have any relation yet and you can delete it.");
                    $this->handleError("This person doesn't have any relation yet and you can delete it.", "این شخص  هیچ رابطه ای ندارد و شما می توانید آن را حذف کنید.", 403);

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
            //throw new Exception("Person has more than one child and cannot be deleted.");

            $this->handleError("Person has more than one child and cannot be deleted.", "فرد بیش از یک فرزند دارد و نمی توان آن را حذف کرد.", 403);

        }

        if ($countChildren == 1 && (!$this->hasParentsPersonOrPersonSpouses($person))) {
            // Log::info("CanDeletePerson: chcking parent of person with the user logged in ".$this->hasParents($personId) );
            Log::info("CanDeletePerson: Removing child relation with parent for Person ID {$personId}");
            return $this->removeChildRelationWithParent($personId, $gender);
        } else {
            //throw new Exception("person parents exist and cannot delete it.");
            $this->handleError("This person doesn't have any relation yet and you can delete it.", "والدین شخصی وجود دارند و نمی توانند آن را حذف کنند.", 403);


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
        Log::info("RemoveMarriage: Attempting to remove marriage(s) for Person ID {$personId}");

        $column = $gender == 1 ? 'man_id' : 'woman_id';

        $marriages = PersonMarriage::where($column, $personId)->get();

        foreach ($marriages as $marriage) {
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->exists();

            if (!$hasChildren) {
                Log::info("RemoveMarriage: No children for marriage ID {$marriage->id}, proceeding with deletion.");

                $marriage->delete();

                Log::info("RemoveMarriage: Deleting partners - Man ID: {$marriage->man_id}, Woman ID: {$marriage->woman_id} must be delete directly");

                $this->removePersonDirectly($marriage->man_id);
                $this->removePersonDirectly($marriage->woman_id);

                return true;
            }

            Log::warning("RemoveMarriage: Cannot remove marriage ID {$marriage->id} because it has children.");
        }

        Log::info("RemoveMarriage: No eligible marriage found for deletion for Person ID {$personId}.");
        return false;
    }


    protected function removeParentRelation($personId, $gender, $downside = false)
    {
        Log::info("RemoveParentRelation: Starting for Person ID {$personId} | Direction: " . ($downside ? 'upside (parent)' : 'downside (child)'));

        if (!$downside) {
            // Case: Person is a child, remove parent-child relationship
            $parentRecord = PersonChild::where('child_id', $personId)->first();

            if (!$parentRecord) {
                Log::info("RemoveParentRelation: No parent-child link found for child Person ID {$personId}");
                return false;
            }

            $parentRecord->delete();
            Log::info("RemoveParentRelation: Parent-child link removed for Person ID {$personId} must be delete directly");

            // Attempt direct deletion of the child
            $this->removePersonDirectly($personId);

            return true;
        }

        // Case: Person is a parent, remove marriage & children relationship
        $column = $gender == 1 ? 'man_id' : 'woman_id';

        $marriages = PersonMarriage::where($column, $personId)->get();

        foreach ($marriages as $marriage) {
            $hasChildren = PersonChild::where('person_marriage_id', $marriage->id)->exists();
            Log::info("RemoveParentRelation: Removing parent marriage relation with child for Person ID {$personId}");

            if ($hasChildren) {
                // Delete all children from this marriage
                PersonChild::where('person_marriage_id', $marriage->id)->delete();
                Log::info("RemoveParentRelation: All children deleted from marriage ID {$marriage->id} must be delete directly");

                // Remove both individuals from marriage
                $this->removePersonDirectlyWithMarriageId($marriage->id);
                return true;
            }
        }

        Log::info("RemoveParentRelation: No marriage with children found for Person ID {$personId}");
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
    protected function removePersonDirectly($personId)
    {
        DB::beginTransaction();
        try {
            $person = Person::find($personId);
            if (!$person) {
                Log::warning("removePersonDirectly: Person ID {$personId} not found.");
                DB::rollBack();
                return false;
            }

            $columnGender = $person->gender == 1 ? 'man_id' : 'woman_id';
            $spouseColumn = $person->gender == 1 ? 'woman_id' : 'man_id';

            $marriages = PersonMarriage::where($columnGender, $personId)->get();
            $spouseIds = collect();

            foreach ($marriages as $marriage) {
                $spouseId = $marriage->$spouseColumn;
                $spouse = Person::find($spouseId);
                if ($spouse) {
                    if ($spouse->is_owner) {
                        Log::info("removePersonDirectly: Cannot delete spouse ID {$spouse->id} (is_owner = true)");
                        DB::rollBack();
                        return false;
                    }
                    $spouseIds->push($spouse->id);
                }
            }

            $relatedPersonIds = $spouseIds->push($personId)->filter()->unique();
            if ($relatedPersonIds->isEmpty()) {
                Log::warning("removePersonDirectly: No related persons found for ID {$personId}");
                DB::rollBack();
                return false;
            }

            $ownersCount = Person::whereIn('id', $relatedPersonIds)->where('is_owner', true)->count();
            if ($ownersCount > 0) {
                Log::info("removePersonDirectly: Cannot delete, at least one person is owner in group: " . $relatedPersonIds->implode(', '));
                DB::rollBack();
                return false;
            }

            // Delete all related persons (person + spouses)
            foreach ($relatedPersonIds as $id) {
                $p = Person::find($id);
                if ($p) {
                    Log::info("removePersonDirectly: Deleting Person ID {$p->id}");
                    $p->delete();
                }
            }

            DB::commit();
            return true;

        } catch (CustomValidationException $e) {
            Log::error("removePersonDirectly: Exception occurred - " . $e->message);
            DB::rollBack();

            throw new CustomValidationException($e->message, $e->endUserMessage, $e->statusCode);
        } catch (Exception $e) {
            Log::error("removePersonDirectly: Exception occurred - " . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }


    protected function removePersonDirectlyWithMarriageId($marriageId)
    {
        DB::beginTransaction();
        try {
            $marriage = PersonMarriage::find($marriageId);
            if (!$marriage) {
                Log::warning("removePersonDirectlyWithMarriageId: Marriage ID {$marriageId} not found.");
                DB::rollBack();
                return false;
            }

            $man = Person::find($marriage->man_id);
            $woman = Person::find($marriage->woman_id);

            // Check ownership before proceeding
            if (($man && $man->is_owner) || ($woman && $woman->is_owner)) {
                Log::info("removePersonDirectlyWithMarriageId: Cannot delete because one or both Marriage ID {$marriageId} is owner.");
                DB::rollBack();
                return false;
            }
            // Remove man
            $this->removePersonDirectly($marriage->man_id);

            // Remove woman
            $this->removePersonDirectly($marriage->woman_id);

            $marriage->delete();

            DB::commit();
            Log::info("removePersonDirectlyWithMarriageId: Marriage ID {$marriageId} and partners successfully removed.");
            return true;

        } catch (CustomValidationException $e) {
            Log::error("removePersonDirectlyWithMarriageId - " . $e->message);
            DB::rollBack();

            throw new CustomValidationException($e->message, $e->endUserMessage, $e->statusCode);
        } catch (Exception $e) {
            Log::error("removePersonDirectlyWithMarriageId: Exception - " . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    protected function handleError($message, $userMessage, $statusCode)
    {
        $this->message = $message;
        $this->endUserMessage = $userMessage;
        $this->statusCode = $statusCode;
        Log::warning("Error: {$message}");
        //return response()->json(['message' => $userMessage], $statusCode);
        throw new CustomValidationException($this->message, $this->endUserMessage, $this->statusCode);
    }

}
