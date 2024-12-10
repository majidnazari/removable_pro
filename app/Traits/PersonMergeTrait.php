<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\Memory;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use GraphQL\Error\Error;
use Exception;
use Log;

trait PersonMergeTrait
{
    use AuthUserTrait;
    protected $userId;

    protected function mergePersonsByIds($primaryPersonId, $secondaryPersonId, $authId)
    {
        Log::info("mergePersonsByIds:" . $primaryPersonId . " - " . $secondaryPersonId . " - " . $authId);

        $this->userId = $authId;

        Log::info("the primaryPersonId is:" . $primaryPersonId);
        Log::info("the secondaryPersonId is:" . $secondaryPersonId);
        Log::info("the authId is:" . $authId);

        DB::beginTransaction();

        try {
            $primaryPerson = Person::where('id', $primaryPersonId)->where('status', Status::Active)->first();
            $secondaryPerson = Person::where('id', $secondaryPersonId)->where('status', Status::Active)->first();


            if ($secondaryPerson->is_owner && !$primaryPerson->is_owner) {
                Log::info("Switching primary and secondary as secondaryPerson is the owner.");
                [$primaryPerson, $secondaryPerson] = [$secondaryPerson, $primaryPerson];
                [$primaryPersonId, $secondaryPersonId] = [$primaryPerson->id, $secondaryPerson->id];
            }

            // Log::info("Switching:" . $primaryPersonId . " - " . $secondaryPersonId);


            if (!$primaryPerson || !$secondaryPerson) {
                throw new Error("One or both persons do not exist.");
            }
            // Check if both persons have the same gender
            if ($primaryPerson->gender !== $secondaryPerson->gender) {
                throw new Error("Persons cannot be merged because they have different genders.");
            }

            $this->mergeMarriages($primaryPersonId, $secondaryPersonId, auth_id: $this->userId);
            $this->mergeChildren($primaryPersonId, $secondaryPersonId, auth_id: $this->userId);

            // Update references in other tables
            $this->updateMemoryReferences($secondaryPersonId, $primaryPersonId);

            $secondaryPerson->editor_id = $this->userId;
            $secondaryPerson->save();
            $secondaryPerson->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            throw new Error("Failed to merge persons: " . $e->getMessage());
        }

        $this->cleanupDuplicates($this->userId);
        return $primaryPerson;
    }

    private function mergeMarriages($primaryPersonId, $secondaryPersonId, $auth_id)
    {
        // Get the gender of the primary person to determine whether to check man_id or woman_id
        $primaryPerson = Person::find($primaryPersonId);
        if (!$primaryPerson) {
            Log::error("Primary person not found: " . $primaryPersonId);
            return;
        }

        // Determine whether to check man_id or woman_id based on gender
        $primaryIsMan = ($primaryPerson->gender === 1); // 1 for man, 0 for woman
        $primaryGenderField = $primaryIsMan ? 'man_id' : 'woman_id';
        $secondaryGenderField = $primaryIsMan ? 'woman_id' : 'man_id';

        // Find all marriages where the secondary person is involved (either as man or woman)
        $query = PersonMarriage::where(function ($query) use ($secondaryPersonId, $secondaryGenderField) {
            $query->where($secondaryGenderField, $secondaryPersonId);
        });

        // Log the SQL query
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $fullSql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        Log::info("mergeMarriages query: " . $fullSql);

        // Iterate through each marriage involving the secondary person
        $query->each(function ($marriage) use ($primaryPersonId, $secondaryPersonId, $auth_id, $primaryGenderField, $secondaryGenderField) {
            // Update the marriage with the primary person's ID
            $marriage->{$primaryGenderField} = $primaryPersonId;  // Update the man_id or woman_id
            $marriage->{$secondaryGenderField} = $primaryPersonId;  // Update the other side of the marriage

            // Update the marriage and save
            $marriage->editor_id = $auth_id;
            $marriage->save();
            Log::info("Updated marriage for primary person ID " . $primaryPersonId . " and secondary person ID " . $secondaryPersonId);
        });

        // Find and update all children of the secondary person
        $childrenQuery = PersonChild::where('child_id', $secondaryPersonId);

        // Log the SQL query for the PersonChild lookup
        $sqlChildren = $childrenQuery->toSql();
        $bindingsChildren = $childrenQuery->getBindings();
        $fullSqlChildren = vsprintf(str_replace('?', '%s', $sqlChildren), $bindingsChildren);
        Log::info("mergeChildren query: " . $fullSqlChildren);

        // Iterate through each child and update the child_id to the primary person
        $childrenQuery->each(function ($child) use ($primaryPersonId, $secondaryPersonId, $auth_id) {
            // Log the child before any update
            Log::info("Updating child with ID: " . $child->id);

            // Update the child reference to primaryPersonId
            if ($child->child_id == $secondaryPersonId) {
                $child->child_id = $primaryPersonId;
                $child->editor_id = $auth_id;
                $child->save();

                Log::info("Updated child to reflect merge: " . json_encode($child));
            }
        });

        // Delete the secondary person
        // After all the references are updated, delete the secondary person
        $secondaryPerson = Person::find($secondaryPersonId);
        if ($secondaryPerson) {
            // Ensure the secondary person exists before attempting to delete
            Log::info("Deleting secondary person with ID: " . $secondaryPersonId);
            $secondaryPerson->delete();  // Delete the secondary person
            Log::info("Successfully deleted secondary person: " . $secondaryPersonId);
        } else {
            // If no secondary person is found by ID, log the error
            Log::error("No secondary person found to delete with ID: " . $secondaryPersonId);
        }
    }


    private function mergeChildren($primaryPersonId, $secondaryPersonId, $auth_id)
    {
        // Get the primary and secondary person to check if they exist
        $primaryPerson = Person::find($primaryPersonId);
        $secondaryPerson = Person::find($secondaryPersonId);

        if (!$primaryPerson || !$secondaryPerson) {
            Log::error("Primary or Secondary person not found.");
            return;
        }

        // Determine whether to check man_id or woman_id based on gender
        $primaryIsMan = ($primaryPerson->gender === 1); // 1 for man, 0 for woman
        $primaryGenderField = $primaryIsMan ? 'man_id' : 'woman_id';
        $secondaryGenderField = $primaryIsMan ? 'woman_id' : 'man_id';

        // Get the person marriage ID based on the primary and secondary person
        $personMarriageIdQuery = PersonMarriage::where(function ($query) use ($primaryPersonId, $secondaryPersonId, $primaryGenderField) {
            // Match person marriage based on primary and secondary person
            $query->where($primaryGenderField, $secondaryPersonId);
        });

        $personMarriage = $personMarriageIdQuery->first();

        if (!$personMarriage) {
            Log::error("No matching marriage found for primaryPersonId: $primaryPersonId and secondaryPersonId: $secondaryPersonId");
            return;
        }

        // Query for all PersonChild records where child_id = secondaryPersonId
        $query = PersonChild::where($secondaryGenderField, $secondaryPersonId);

        // Log the SQL query for the PersonChild lookup
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $fullSql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        Log::info("mergeChildren query: " . $fullSql);

        // Iterate through each PersonChild record and update accordingly
        $query->each(function ($child) use ($primaryPersonId, $secondaryPersonId, $auth_id, $personMarriage, $primaryGenderField, $secondaryGenderField) {
            // Log the child before any update
            Log::info("Updating child with ID: " . $child->id);

            // Update all references where child_id = secondaryPersonId to primaryPersonId
            if ($child->{$secondaryGenderField} == $secondaryPersonId) {
                // Update child_id to primaryPersonId
                $child->child_id = $primaryPersonId;
                // Update person_marriage_id to reflect the correct marriage
                $child->person_marriage_id = $personMarriage->id;
                $child->editor_id = $auth_id;
                $child->save();

                Log::info("Updated child to reflect merge: " . json_encode($child));
            }
        });

        // After all the references are updated, delete the secondary person (secondaryPersonId)
        $secondaryPerson->delete();
        Log::info("Deleted secondary person: " . $secondaryPersonId);
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
            $duplicates->shift(); // Keep the first record in each group
            $duplicates->each(function ($marriage) use ($auth_id) {
                $marriage->editor_id = $auth_id;
                $marriage->save();
                $marriage->delete(); // Delete the rest in each group
            });
        });

        // Clean up duplicate children relationships across the entire PersonChild table
        PersonChild::all()->groupBy(function ($child) {
            return $child->child_id . '-' . $child->person_marriage_id;
        })->each(function ($duplicates) use ($auth_id) {
            $duplicates->shift(); // Keep the first record in each group
            $duplicates->each(function ($child) use ($auth_id) {
                $child->editor_id = $auth_id;
                $child->save();
                $child->delete(); // Delete the rest in each group
            });
        });
    }

}