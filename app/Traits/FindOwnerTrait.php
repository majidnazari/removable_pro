<?php

namespace App\Traits;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
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
    

}

