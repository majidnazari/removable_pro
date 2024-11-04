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
        $primaryPersonId = min($args['primaryPersonId'], $args['secondaryPersonId']);
        $secondaryPersonId = max($args['primaryPersonId'], $args['secondaryPersonId']);

        DB::beginTransaction();

        try {
            $primaryPerson = Person::find($primaryPersonId);
            $secondaryPerson = Person::find($secondaryPersonId);

            if (!$primaryPerson || !$secondaryPerson) {
                throw new Error("One or both persons do not exist.");
            }

            $this->mergeMarriages($primaryPersonId, $secondaryPersonId);
            $this->mergeChildren($primaryPersonId, $secondaryPersonId);

            $secondaryPerson->delete();
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new Error("Failed to merge persons: " . $e->getMessage());
        }

        $this->cleanupDuplicates($primaryPersonId);
        return $primaryPerson;
    }

    private function mergeMarriages($primaryPersonId, $secondaryPersonId)
    {
        PersonMarriage::where(function ($query) use ($primaryPersonId, $secondaryPersonId) {
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
                PersonChild::where('person_marriage_id', $marriage->id)
                    ->update(['person_marriage_id' => $existingMarriage->id]);
                $marriage->delete();
            } else {
                $marriage->man_id = $marriage->man_id == $secondaryPersonId ? $primaryPersonId : $marriage->man_id;
                $marriage->woman_id = $marriage->woman_id == $secondaryPersonId ? $primaryPersonId : $marriage->woman_id;
                $marriage->save();
            }
        });
    }

    private function mergeChildren($primaryPersonId, $secondaryPersonId)
    {
        PersonChild::where(function ($query) use ($secondaryPersonId) {
            $query->where('child_id', $secondaryPersonId)
                  ->orWhere('person_marriage_id', $secondaryPersonId);
        })->each(function ($child) use ($primaryPersonId, $secondaryPersonId) {
            $existingChild = PersonChild::where('child_id', $primaryPersonId)
                ->where('person_marriage_id', $child->person_marriage_id)
                ->first();

            if ($existingChild) {
                $child->delete();
            } else {
                $child->child_id = $child->child_id == $secondaryPersonId ? $primaryPersonId : $child->child_id;
                $child->person_marriage_id = $child->person_marriage_id == $secondaryPersonId ? $primaryPersonId : $child->person_marriage_id;
                $child->save();
            }
        });
    }

    private function cleanupDuplicates()
    {
        // Step 1: Clean up duplicate marriages across the entire PersonMarriage table
        PersonMarriage::all()->groupBy(function ($marriage) {
            // Group by unique man-woman pairs to detect duplicates
            return $marriage->man_id . '-' . $marriage->woman_id;
        })->each(function ($duplicates) {
            $duplicates->shift(); // Keep the first record in each group
            $duplicates->each->delete(); // Delete the rest in each group
        });
    
        // Step 2: Clean up duplicate children relationships across the entire PersonChild table
        PersonChild::all()->groupBy(function ($child) {
            // Group by unique child and person_marriage_id to detect duplicates
            return $child->child_id . '-' . $child->person_marriage_id;
        })->each(function ($duplicates) {
            $duplicates->shift(); // Keep the first record in each group
            $duplicates->each->delete(); // Delete the rest in each group
        });
    }
    
}
