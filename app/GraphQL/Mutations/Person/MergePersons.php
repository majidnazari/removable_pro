<?php

namespace App\GraphQL\Mutations\Person;

use App\GraphQL\Enums\ConfirmMemoryStatus;
use App\Models\Person;
use App\Models\Memory;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\GetAllBloodPersonsInClanFromHeads;
use App\Traits\GetAllBloodPersonsInClanFromHeadsAccordingPersonId;
use App\Traits\ValidateMergeTwoBloodyPeopleTrait;
use App\Traits\ValidateMergeTwoSubGroupPeopleTrait;
use Exception;
use App\Exceptions\CustomValidationException;


use Log;

final class MergePersons
{
    use AuthUserTrait;
    use GetAllBloodPersonsInClanFromHeads;
    use GetAllBloodPersonsInClanFromHeadsAccordingPersonId;
    use ValidateMergeTwoBloodyPeopleTrait;
    use ValidateMergeTwoSubGroupPeopleTrait;
    protected $userId;


    public function resolvemerge($rootValue, array $args)
    {

        $this->userId = $this->getUserId();



        $primaryPersonId = min($args['primaryPersonId'], $args['secondaryPersonId']);
        $secondaryPersonId = max($args['primaryPersonId'], $args['secondaryPersonId']);


        DB::beginTransaction();

        try {
            $primaryPerson = Person::where('id', $primaryPersonId)->where('status', Status::Active)->first();
            $secondaryPerson = Person::where('id', $secondaryPersonId)->where('status', Status::Active)->first();


            if ($secondaryPerson->is_owner && !$primaryPerson->is_owner) {
                [$primaryPerson, $secondaryPerson] = [$secondaryPerson, $primaryPerson];
                [$primaryPersonId, $secondaryPersonId] = [$primaryPerson->id, $secondaryPerson->id];
            }

            $this->validateSubGroupMerge($primaryPerson->id, $secondaryPerson->id);
            $this->validatePersonsForMerge($primaryPerson, $secondaryPerson);

            // Check if both persons have the same gender
            if ($primaryPerson->gender !== $secondaryPerson->gender) {
                throw new CustomValidationException("Persons cannot be merged because they have different genders.", "افراد را نمی توان ادغام کرد زیرا جنسیت های متفاوتی دارند.", 403);

                //throw new Error("Persons cannot be merged because they have different genders.");
            }
            if (($secondaryPerson->is_owner == 1) && ($primaryPerson->is_owner == 1)) {
                throw new CustomValidationException("two people you have selected  both  are owner!", "دو نفری که انتخاب کرده اید هر دو صاحب هستند!", 403);

            }
            $this->mergeMarriages($primaryPerson, $secondaryPerson, auth_id: $this->userId);

            // Update references in other tables
            $this->updateMemoryReferences($secondaryPersonId, $primaryPersonId);

            $secondaryPerson->editor_id = $this->userId;
            $secondaryPerson->save();
            $secondaryPerson->delete();

            DB::commit();

        } catch (CustomValidationException $e) {
            DB::rollback();

            throw new CustomValidationException("Failed to merge persons: ", "ادغام افراد ناموفق بود:", 500);

        } catch (Exception $e) {
            DB::rollback();

            throw new Error("Failed to merge persons: " . $e->getMessage());
        }

        $this->cleanupDuplicates($this->userId);
        return $primaryPerson;
    }

    private function mergeMarriages($primaryPerson, $secondaryPerson, $auth_id)
    {
        //$primaryPerson = Person::find($primaryPersonId);
        if (!$primaryPerson) {
            return;
        }

        // Determine whether to check man_id or woman_id based on gender
        $primaryIsMan = ($primaryPerson->gender == 1); // 1 for man, 0 for woman
        $secondaryGenderField = $primaryGenderField = $primaryIsMan ? 'man_id' : 'woman_id';


        // Find all marriages where the secondary person is involved (either as man or woman)
        $query = PersonMarriage::where(function ($query) use ($secondaryPerson, $secondaryGenderField) {
            $query->where($secondaryGenderField, $secondaryPerson->id);
        });

        // Log the SQL query
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $fullSql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //       Log::info("mergeMarriages query: " . $fullSql);

        // Iterate through each marriage involving the secondary person
        $query->each(function ($marriage) use ($primaryPerson, $secondaryPerson, $auth_id, $primaryGenderField, $secondaryGenderField) {
            // Update the marriage with the primary person's ID
            $marriage->{$primaryGenderField} = $primaryPerson->id;  // Update the man_id or woman_id
            //$marriage->{$secondaryGenderField} = $primaryPersonId->id;  // Update the other side of the marriage

            // Update the marriage and save
            $marriage->editor_id = $auth_id;
            $marriage->save();
        });

        // Find and update all children of the secondary person
        $childrenQuery = PersonChild::where('child_id', $secondaryPerson->id);

        // Log the SQL query for the PersonChild lookup
        $sqlChildren = $childrenQuery->toSql();
        $bindingsChildren = $childrenQuery->getBindings();
        $fullSqlChildren = vsprintf(str_replace('?', '%s', $sqlChildren), $bindingsChildren);

        // Iterate through each child and update the child_id to the primary person
        $childrenQuery->each(function ($child) use ($primaryPerson, $secondaryPerson, $auth_id) {

            // Update the child reference to primaryPersonId
            if ($child->child_id == $secondaryPerson->id) {
                $child->child_id = $primaryPerson->id;
                $child->editor_id = $auth_id;
                $child->save();

                //               Log::info("Updated child to reflect merge: " . json_encode($child));
            }
        });

        // Delete the secondary person
        // After all the references are updated, delete the secondary person
        $secondaryPerson = Person::find($secondaryPerson->id);
        if ($secondaryPerson) {

            $secondaryPerson->delete();  // Delete the secondary person
        }
    }


    private function mergeChildren($primaryPerson, $secondaryPerson, $auth_id)
    {


        if (!$primaryPerson || !$secondaryPerson) {
            //Log::error("Primary or Secondary person not found.");
            return;
        }


        // Query for all PersonChild records where child_id = secondaryPersonId
        $query = PersonChild::where('child_id', $secondaryPerson->id);

        // Log the SQL query for the PersonChild lookup
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $fullSql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //       Log::info("mergeChildren query: " . $fullSql);

        // Iterate through each PersonChild record and update accordingly
        $query->each(function ($child) use ($primaryPerson, $secondaryPerson, $auth_id) {
            // Log the child before any update
//           Log::info("Updating child with ID: " . $child->id);

            // Update all references where child_id = secondaryPersonId to primaryPersonId
            if ($child->child_id == $secondaryPerson->id) {
                // Update child_id to primaryPersonId
                $child->child_id = $primaryPerson->id;
                // Update person_marriage_id to reflect the correct marriage
                //$child->person_marriage_id = $personMarriage->id;
                $child->editor_id = $auth_id;
                $child->save();

                //               Log::info("Updated child to reflect merge: " . json_encode($child));
            }
        });

        // After all the references are updated, delete the secondary person (secondaryPersonId)
        $secondaryPerson->delete();
        //       Log::info("Deleted secondary person: " . $secondaryPerson->id);
    }


    // Update Memory references
    private function updateMemoryReferences($oldPersonId, $newPersonId)
    {

        // Check if the new person is an owner
        $isOwner = Person::where('id', $newPersonId)->value('is_owner');

        Memory::where('person_id', $oldPersonId)->chunk(100, function ($memories) use ($newPersonId, $isOwner) {
            foreach ($memories as $memory) {
                // Update the person_id reference
                $memory->person_id = $newPersonId;

                // If the new person is an owner, set confirm_status = 3
                if ($isOwner) {
                    $memory->confirm_status = ConfirmMemoryStatus::Suspend;
                }

                $memory->save(); // Persist changes
            }
        });
    }

    private function cleanupDuplicates($auth_id)
    {
        // Clean up duplicate marriages across the entire PersonMarriage table
        PersonMarriage::all()->groupBy(function ($marriage) {
            return $marriage->man_id . '-' . $marriage->woman_id;
        })->each(function ($duplicates) use ($auth_id) {
            // Keep the first record in each group and remove the rest
            $firstMarriage = $duplicates->shift(); // Keep the first record

            $duplicates->each(function ($marriage) use ($auth_id, $firstMarriage) {
                // Log the marriage being deleted

                // Update the corresponding PersonChild entries before deleting the duplicate marriage
                $updatedCount = PersonChild::where('person_marriage_id', $marriage->id)
                    ->update(['person_marriage_id' => $firstMarriage->id, 'editor_id' => $auth_id]);


                $marriage->editor_id = $auth_id;
                $marriage->save();
                $marriage->delete(); // Delete the duplicate marriage
            });
        });

        // Clean up duplicate children relationships across the entire PersonChild table
        PersonChild::all()->groupBy(function ($child) {
            return $child->child_id . '-' . $child->person_marriage_id;
        })->each(function ($duplicates) use ($auth_id) {
            // Keep the first record in each group and remove the rest
            $firstChild = $duplicates->shift(); // Keep the first record

            $duplicates->each(function ($child) use ($auth_id) {

                $child->editor_id = $auth_id;
                $child->save();
                $child->delete(); // Delete the duplicate child
            });
        });
    }



}
