<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\Memory;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use GraphQL\Error\Error;
use App\Exceptions\CustomValidationException;

use Exception;
use Log;

trait PersonMergeTrait
{
    use AuthUserTrait;
    protected $userId;

    protected function mergePersonsByIds($primaryPersonId, $secondaryPersonId, $authId)
    {

        $this->userId = $authId;

        DB::beginTransaction();

        try {
            $primaryPerson = Person::where('id', $primaryPersonId)->where('status', Status::Active)->first();
            $secondaryPerson = Person::where('id', $secondaryPersonId)->where('status', Status::Active)->first();

            if ($secondaryPerson->is_owner && !$primaryPerson->is_owner) {
                [$primaryPerson, $secondaryPerson] = [$secondaryPerson, $primaryPerson];
                [$primaryPersonId, $secondaryPersonId] = [$primaryPerson->id, $secondaryPerson->id];
            }

            if (!$primaryPerson || !$secondaryPerson) {
                //throw new Error("One or both persons do not exist.");
                throw new CustomValidationException("One or both persons do not exist.", "یک یا هر دو نفر وجود ندارند.", 400);
            }
            // Check if both persons have the same gender
            if ($primaryPerson->gender !== $secondaryPerson->gender) {
                //throw new Error("Persons cannot be merged because they have different genders.");

                throw new CustomValidationException("Persons cannot be merged because they have different genders.", "افراد را نمی توان ادغام کرد زیرا جنسیت های متفاوتی دارند.", 400);

            }
            if (($secondaryPerson->is_owner == 1) && ($primaryPerson->is_owner == 1)) {
                // throw new Error("two people you have selected  both  are owner!");
                throw new CustomValidationException("One or both persons do not exist.", "دو نفری که انتخاب کرده اید هر دو صاحب هستند!", 400);

            }

            $this->mergeMarriages($primaryPerson, $secondaryPerson, auth_id: $this->userId);
            //$this->mergeChildren($primaryPerson, $secondaryPerson, auth_id: $this->userId);

            // Update references in other tables
            $this->updateMemoryReferences($secondaryPersonId, $primaryPersonId);

            $secondaryPerson->editor_id = $this->userId;
            $secondaryPerson->save();
            $secondaryPerson->delete();

            DB::commit();

        } catch (CustomValidationException $e) {
            Log::error("Failed to merge persons: " . $e->getMessage());
            DB::rollBack();

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), $e->getStatusCode());
        } catch (Exception $e) {
            DB::rollback();
            throw new Error("Failed to merge persons: " . $e->getMessage());
        }

        $this->cleanupDuplicates($this->userId);
        return $primaryPerson;
    }

    private function mergeMarriages($primaryPerson, $secondaryPerson, $auth_id)
    {
        if (!$primaryPerson) {
            // Log::error("Primary person not found: " . $primaryPerson);
            return;
        }

        // Determine whether to check man_id or woman_id based on gender
        $primaryIsMan = ($primaryPerson->gender == 1); // 1 for man, 0 for woman
        $secondaryGenderField = $primaryGenderField = $primaryIsMan ? 'man_id' : 'woman_id';

        //       Log::info("gender {$primaryPerson->gender} is for id {$primaryPerson->id} ");

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
            //           Log::info("Updated marriage for primary person ID " . $primaryPerson->id);//. " and secondary person ID " . $secondaryPerson->id);
        });

        // Find and update all children of the secondary person
        $childrenQuery = PersonChild::where('child_id', $secondaryPerson->id);

        // Log the SQL query for the PersonChild lookup
        $sqlChildren = $childrenQuery->toSql();
        $bindingsChildren = $childrenQuery->getBindings();
        $fullSqlChildren = vsprintf(str_replace('?', '%s', $sqlChildren), $bindingsChildren);
        //       Log::info("mergeChildren query: " . $fullSqlChildren);

        // Iterate through each child and update the child_id to the primary person
        $childrenQuery->each(function ($child) use ($primaryPerson, $secondaryPerson, $auth_id) {
            // Log the child before any update
//           Log::info("Updating child with ID: " . $child->id);

            // Update the child reference to primaryPersonId
            if ($child->child_id == $secondaryPerson->id) {
                $child->child_id = $primaryPerson->id;
                $child->editor_id = $auth_id;
                $child->save();

                //               Log::info("Updated child to reflect merge: " . json_encode($child));
            }
        });

        $secondaryPerson = Person::find($secondaryPerson->id);
        if ($secondaryPerson) {

            $secondaryPerson->delete();  // Delete the secondary person
//           Log::info("Successfully deleted secondary person: " . $secondaryPerson->id);
        }
    }

    private function mergeChildren($primaryPerson, $secondaryPerson, $auth_id)
    {

        if (!$primaryPerson || !$secondaryPerson) {
            // Log::error("Primary or Secondary person not found.");
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

            // Update all references where child_id = secondaryPersonId to primaryPersonId
            if ($child->child_id == $secondaryPerson->id) {
                // Update child_id to primaryPersonId
                $child->child_id = $primaryPerson->id;
                // Update person_marriage_id to reflect the correct marriage
                //$child->person_marriage_id = $personMarriage->id;
                $child->editor_id = $auth_id;
                $child->save();

            }
        });

        // After all the references are updated, delete the secondary person (secondaryPersonId)
        $secondaryPerson->delete();
        //       Log::info("Deleted secondary person: " . $secondaryPerson->id);
    }

    // Update Memory references
    private function updateMemoryReferences($oldPersonId, $newPersonId)
    {
        Memory::where('person_id', $oldPersonId)->chunk(100, function ($events) use ($newPersonId) {
            foreach ($events as $event) {
                // You update the record, but you still need to call `save()` to persist the change
                $event->person_id = $newPersonId;
                $event->save(); // Save after updating the record
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

                // Update the corresponding PersonChild entries before deleting the duplicate marriage
                $updatedCount = PersonChild::where('person_marriage_id', $marriage->id)
                    ->update(['person_marriage_id' => $firstMarriage->id, 'editor_id' => $auth_id]);

                // Mark the duplicate marriage as deleted
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

                // Delete the duplicate child record
                $child->editor_id = $auth_id;
                $child->save();
                $child->delete(); // Delete the duplicate child
            });
        });
    }

}