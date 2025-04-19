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
//       Log::info("personSpousesOwner person is :" . json_encode($person));
        $genderColumn=$person->gender == 1 ? 'man_id' : 'woman_id';
       
        // Get all spouse relationships based on gender
        $spouseIds = PersonMarriage::where($genderColumn, $person->id)
        ->pluck(($person->gender == 1) ? 'woman_id' : 'man_id');

//       Log::info("the all spouses spouseOwner :" . $spouseIds);

        if ($spouseIds->isEmpty()) {
//           Log::info("No spouses found for person ID: " . $person->id);
            return false;
        }
        
        // Check if any of the retrieved spouse IDs belong to an owner in Person table
        $spouseOwnerExists = Person::whereIn('id', $spouseIds)
            ->where('is_owner', 1)
            ->exists(); // Correctly checks if at least one spouse is an owner
        
//       Log::info("Spouse owner exists: " . json_encode($spouseOwnerExists));
        return $spouseOwnerExists;
    }

    public function checkPersonSpousesOwner( $person): bool
    {
        $spouseOwners = $this->personSpousesOwner($person);
        return  $spouseOwners ;
    }



}

