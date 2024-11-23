<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

final class MergePersons
{
    protected $userId;

    public function resolvemerge($rootValue, array $args)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        //Log::info("the user id is:" .  $auth_id);

        $primaryPersonId = min($args['primaryPersonId'], $args['secondaryPersonId']);
        $secondaryPersonId = max($args['primaryPersonId'], $args['secondaryPersonId']);

        DB::beginTransaction();

        try {
            $primaryPerson = Person::where('id',$primaryPersonId)->where('status',Status::Active)->first();
            $secondaryPerson = Person::where('id',$secondaryPersonId)->where('status',Status::Active)->first();;

            if (!$primaryPerson || !$secondaryPerson) {
                throw new Error("One or both persons do not exist.");
            }
            // Check if both persons have the same gender
            if ($primaryPerson->gender !== $secondaryPerson->gender) {
                throw new Error("Persons cannot be merged because they have different genders.");
            }

            $this->mergeMarriages($primaryPersonId, $secondaryPersonId, auth_id: $this->userId);
            $this->mergeChildren($primaryPersonId, $secondaryPersonId, auth_id: $this->userId);

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

        //Log::info("merge of marriage is running and the params are:" .$primaryPersonId . " - " . $secondaryPersonId . " - ".  $auth_id);
        PersonMarriage::where(function ($query) use ($primaryPersonId, $secondaryPersonId) {
            $query->where('man_id', $secondaryPersonId)
                ->orWhere('woman_id', $secondaryPersonId);
        })->each(function ($marriage) use ($primaryPersonId, $secondaryPersonId, $auth_id) {

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
                    ->update(['person_marriage_id' => $existingMarriage->id,'editor_id' =>$auth_id ]);
                //$marriage->editor_id = $auth_id;  // Set the editor ID
                $marriage->save();
                $marriage->delete();
       //Log::info("existingMarriage:" .$primaryPersonId . " - " . $secondaryPersonId . " - ".  $auth_id);

            } else {
                $marriage->man_id = $marriage->man_id == $secondaryPersonId ? $primaryPersonId : $marriage->man_id;
                $marriage->woman_id = $marriage->woman_id == $secondaryPersonId ? $primaryPersonId : $marriage->woman_id;
                $marriage->editor_id = $auth_id;  // Set the editor ID
                $marriage->save();
        //Log::info("else of existingMarriage:" .$primaryPersonId . " - " . $secondaryPersonId . " - ".  $auth_id);

            }
        });
    }

    private function mergeChildren($primaryPersonId, $secondaryPersonId, $auth_id)
    {
        PersonChild::where(function ($query) use ($secondaryPersonId) {
            $query->where('child_id', $secondaryPersonId)
                ->orWhere('person_marriage_id', $secondaryPersonId);
        })->each(function ($child) use ($primaryPersonId, $secondaryPersonId, $auth_id) {
            $existingChild = PersonChild::where('child_id', $primaryPersonId)
                ->where('person_marriage_id', $child->person_marriage_id)
                ->first();

            if ($existingChild) {
                $child->editor_id = $auth_id;  // Set the editor ID
                $child->save();
                $child->delete();
        //Log::info("existingChild:" .$primaryPersonId . " - " . $secondaryPersonId . " - ".  $auth_id);

            } else {
                $child->child_id = $child->child_id == $secondaryPersonId ? $primaryPersonId : $child->child_id;
                $child->person_marriage_id = $child->person_marriage_id == $secondaryPersonId ? $primaryPersonId : $child->person_marriage_id;
                $child->editor_id = $auth_id;  // Set the editor ID
                $child->save();

        //Log::info("not existingChild:" .$primaryPersonId . " - " . $secondaryPersonId . " - ".  $auth_id);

            }
        });
    }

    // private function cleanupDuplicates()
    // {
    //     // Step 1: Clean up duplicate marriages across the entire PersonMarriage table
    //     PersonMarriage::all()->groupBy(function ($marriage) {
    //         // Group by unique man-woman pairs to detect duplicates
    //         return $marriage->man_id . '-' . $marriage->woman_id;
    //     })->each(function ($duplicates) {
    //         $duplicates->shift(); // Keep the first record in each group
    //         $duplicates->each->delete(); // Delete the rest in each group
    //     });

    //     // Step 2: Clean up duplicate children relationships across the entire PersonChild table
    //     PersonChild::all()->groupBy(function ($child) {
    //         // Group by unique child and person_marriage_id to detect duplicates
    //         return $child->child_id . '-' . $child->person_marriage_id;
    //     })->each(function ($duplicates) {
    //         $duplicates->shift(); // Keep the first record in each group
    //         $duplicates->each->delete(); // Delete the rest in each group
    //     });
    // }

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
