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

trait PersonMergeTrait_tmp
{
    protected function mergePersonsByIds($primaryPersonId, $secondaryPersonId, $authId)
    {
        DB::beginTransaction();

        try {
            $primaryPerson = Person::where('id', $primaryPersonId)->where('status', Status::Active)->first();
            $secondaryPerson = Person::where('id', $secondaryPersonId)->where('status', Status::Active)->first();

           // Prioritize is_owner = 1 as the primary person
        if ($secondaryPerson->is_owner && !$primaryPerson->is_owner) {
            Log::info("Switching primary and secondary as secondaryPerson is the owner.");
            [$primaryPerson, $secondaryPerson] = [$secondaryPerson, $primaryPerson];
            [$primaryPersonId, $secondaryPersonId] = [$primaryPerson->id, $secondaryPerson->id];
        }

           // Log::info("the primaryPerson is:" .$primaryPersonId. json_encode($primaryPerson));
            //Log::info("the secondaryPerson is:" .$secondaryPersonId. json_encode($secondaryPerson));
            //Log::info("the authId is:" .$authId);
            if (!$primaryPerson || !$secondaryPerson) {
                throw new Error("One or both persons do not exist.");
            }

            // Check if both persons have the same gender
            if ($primaryPerson->gender !== $secondaryPerson->gender) {
                throw new Error("Persons cannot be merged because they have different genders.");
            }

          

            $this->mergeMarriages($primaryPersonId, $secondaryPersonId, $authId);
            $this->mergeChildren($primaryPersonId, $secondaryPersonId, $authId);

            // Update references in other tables
            $this->updateMemoryReferences($secondaryPersonId, $primaryPersonId);


            // Mark secondary person as deleted
            $secondaryPerson->editor_id = $authId;
            $secondaryPerson->save();
            $secondaryPerson->delete();

            DB::commit();
            return $primaryPerson;

        } catch (Exception $e) {
            DB::rollback();
            throw new Error("Failed to merge persons: " . $e->getMessage());
        }
    }

    private function mergeMarriages($primaryPersonId, $secondaryPersonId, $authId)
    {
        PersonMarriage::where(function ($query) use ($primaryPersonId, $secondaryPersonId) {
            $query->where('man_id', $secondaryPersonId)
                ->orWhere('woman_id', $secondaryPersonId);
        })->each(function ($marriage) use ($primaryPersonId, $secondaryPersonId, $authId) {
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
                    ->update(['person_marriage_id' => $existingMarriage->id, 'editor_id' => $authId]);
                $marriage->delete();
            } else {
                $marriage->man_id = $marriage->man_id == $secondaryPersonId ? $primaryPersonId : $marriage->man_id;
                $marriage->woman_id = $marriage->woman_id == $secondaryPersonId ? $primaryPersonId : $marriage->woman_id;
                $marriage->editor_id = $authId;
                $marriage->save();
            }
        });
    }

    private function mergeChildren($primaryPersonId, $secondaryPersonId, $authId)
    {
        PersonChild::where(function ($query) use ($secondaryPersonId) {
            $query->where('child_id', $secondaryPersonId)
                ->orWhere('person_marriage_id', $secondaryPersonId);
        })->each(function ($child) use ($primaryPersonId, $secondaryPersonId, $authId) {
            $existingChild = PersonChild::where('child_id', $primaryPersonId)
                ->where('person_marriage_id', $child->person_marriage_id)
                ->first();

            if ($existingChild) {
                $child->delete();
            } else {
                $child->child_id = $child->child_id == $secondaryPersonId ? $primaryPersonId : $child->child_id;
                $child->person_marriage_id = $child->person_marriage_id == $secondaryPersonId ? $primaryPersonId : $child->person_marriage_id;
                $child->editor_id = $authId;
                $child->save();
            }
        });
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
}
