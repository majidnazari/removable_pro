<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
trait FindOwnerTrait
{
    /**
     * Get the person record for the logged-in user where is_owner = 1.
     *
     * @return Person|null
     */
    public function findOwner()
    {
        // Get the logged-in user ID
        $userId = Auth::guard('api')->id();

        // Find the person where is_owner = 1 and user_id matches
        return Person::where('creator_id', $userId)
            ->where('is_owner', 1)
            ->first();
    }
}

