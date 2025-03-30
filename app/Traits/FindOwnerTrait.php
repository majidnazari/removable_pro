<?php

namespace App\Traits;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Models\PersonMarriage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
trait FindOwnerTrait
{
    /**
     * Get the person record for the logged-in user where is_owner = 1.
     *
     * @return Person|null
     */
    public function findOwner($user_id = null)
    {
        // Determine the user ID to query
        $userId = $user_id ?? Auth::guard('api')->id();

        // Query the Person model for the active owner
        return Person::where('creator_id', $userId)
            ->where('status', Status::Active)
            ->where('is_owner', 1)
            ->first();
    }
    public function personSpousesOwner( $person)
    {
        Log::info("personSpousesOwner person is :" . json_encode($person));
        $genderColumn=$person->gender == 1 ? 'man_id' : 'woman_id';
        // $userId = $user_id ?? Auth::guard('api')->id();

        // // First get the person's record to determine gender
        // $person = Person::where('creator_id', $userId)
        //     ->where('status', Status::Active)
        //     ->first();

        // if (!$person) {
        //     Log::info("No active person found for user {$userId}");
        //     return null;
        // }

        // Get all spouse relationships based on gender

        $spouseIds = PersonMarriage::where($genderColumn, $person->id)
        ->pluck(($person->gender == 1) ? 'woman_id' : 'man_id');
       // ->get();
        Log::info("the all spouses spouseOwner :" . $spouseIds);

        if ($spouseIds->isEmpty()) {
            Log::info("No spouses found for person ID: " . $person->id);
            return false;
        }
        
        // Check if any of the retrieved spouse IDs belong to an owner in Person table
        $spouseOwnerExists = Person::whereIn('id', $spouseIds)
            ->where('is_owner', 1)
            ->exists(); // Correctly checks if at least one spouse is an owner
        
        Log::info("Spouse owner exists: " . json_encode($spouseOwnerExists));
        
        return $spouseOwnerExists;
      
        //->where('creator_id',$userId)
        //->pluck(($person->gender == 1) ? 'woman_id' : 'man_id');

        // if ($person->gender == 1) { // Male
        //     $spousePersonIds = DB::table('person_marriages')
        //         ->where('man_id', $person->id)
        //         ->whereNull('deleted_at')
        //         ->pluck('woman_id');
        // } else { // Female
        //     $spousePersonIds = DB::table('person_marriages')
        //         ->where('woman_id', $person->id)
        //         ->whereNull('deleted_at')
        //         ->pluck('man_id');
        // }

        // if ($spousePersonIds->isEmpty()) {
        //     Log::info("No spouses found for person {$person->id}");
        //     return null;
        // }

        // // Check if any spouses are owners
        // return Person::whereIn('id', $spousePersonIds)
        //     ->where('is_owner', 1)
        //     ->where('status', Status::Active)
        //     ->get();
    }

    public function checkPersonSpousesOwner( $person): bool
    {
       
        $spouseOwners = $this->personSpousesOwner($person);

        return  $spouseOwners ;

        // Log::info("The person owner is: " . json_encode($owner));
        // Log::info("The spouse owners are: " . json_encode($spouseOwners));

        // // Check if the main person is an owner
        // if ($owner && $owner->is_owner == 1) {
        //     return true;
        // }

        // // Check if any spouse is an owner
        // if ($spouseOwners) {
        //     foreach ($spouseOwners as $spouseOwner) {
        //         if ($spouseOwner->is_owner == 1) {
        //             return true;
        //         }
        //     }
        // }

        // return false;
    }



}

