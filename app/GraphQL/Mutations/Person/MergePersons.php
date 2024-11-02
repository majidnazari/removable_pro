<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\DB;

final class MergePersons
{
    public function resolvemerge($rootValue, array $args)
    {
        // Ensure the larger ID is assigned to $secondaryPersonId for deletion
        $primaryPersonId = min($args['primaryPersonId'], $args['secondaryPersonId']);
        $secondaryPersonId = max($args['primaryPersonId'], $args['secondaryPersonId']);

        // Start transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Retrieve primary and secondary persons
            $primaryPerson = Person::find($primaryPersonId);
            $secondaryPerson = Person::find($secondaryPersonId);

            if (!$primaryPerson || !$secondaryPerson) {
                throw new Error("One or both persons do not exist.");
            }

            // Step 1: Merge marriages
            $this->mergeMarriages($primaryPersonId, $secondaryPersonId);

            // Step 2: Merge children relationships
            $this->mergeChildren($primaryPersonId, $secondaryPersonId);

            // Step 3: Delete the secondary person (larger ID)
            $secondaryPerson->delete();

            // Commit the transaction
            DB::commit();

            return $primaryPerson;

        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollback();
            throw new Error("Failed to merge persons: " . $e->getMessage());
        }
    }

    private function mergeMarriages($primaryPersonId, $secondaryPersonId)
    {
        PersonMarriage::where(function ($query) use ($secondaryPersonId) {
            $query->where('man_id', $secondaryPersonId)
                  ->orWhere('woman_id', $secondaryPersonId);
        })->each(function ($marriage) use ($primaryPersonId, $secondaryPersonId) {
            
            $existingMarriage = PersonMarriage::where(function ($query) use ($primaryPersonId, $marriage) {
                $query->where(function ($q) use ($primaryPersonId, $marriage) {
                    $q->where('man_id', $primaryPersonId)
                      ->where('woman_id', $marriage->woman_id);
                })->orWhere(function ($q) use ($primaryPersonId, $marriage) {
                    $q->where('man_id', $marriage->man_id)
                      ->where('woman_id', $primaryPersonId);
                });
            })->first();

            if ($existingMarriage) {
                // Update all child records referencing the duplicate marriage to point to the existing one
                PersonChild::where('person_marriage_id', $marriage->id)
                    ->update(['person_marriage_id' => $existingMarriage->id]);

                // Delete the duplicate marriage
                $marriage->delete();
            } else {
                // Update marriage to point to the primary person
                $marriage->man_id = $marriage->man_id == $secondaryPersonId ? $primaryPersonId : $marriage->man_id;
                $marriage->woman_id = $marriage->woman_id == $secondaryPersonId ? $primaryPersonId : $marriage->woman_id;
                $marriage->save();
            }
        });
    }

    private function mergeChildren($primaryPersonId, $secondaryPersonId)
    {
        PersonChild::where('child_id', $secondaryPersonId)
            ->orWhere('person_marriage_id', $secondaryPersonId)
            ->each(function ($child) use ($primaryPersonId, $secondaryPersonId) {
                $existingChild = PersonChild::where('child_id', $primaryPersonId)
                    ->where('person_marriage_id', $child->person_marriage_id)
                    ->first();

                if ($existingChild) {
                    // Delete the duplicate child relationship
                    $child->delete();
                } else {
                    // Update child relationship to point to the primary person
                    $child->child_id = $child->child_id == $secondaryPersonId ? $primaryPersonId : $child->child_id;
                    $child->person_marriage_id = $child->person_marriage_id == $secondaryPersonId ? $primaryPersonId : $child->person_marriage_id;
                    $child->save();
                }
            });
    }
}
