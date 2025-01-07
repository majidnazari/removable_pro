<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status; // Make sure the MergeStatus Enum is imported

trait GetAllowedAllUsersInClan
{
    /**
     * Get allowed creator IDs for a user, including their connected users where the merge status is "Complete".
     *
     * @param  int  $userId
     * @return array
     */
    protected function getAllowedUserIds(int $userId): array
    {
        // Start with the logged-in user ID
        $allowedAllUsersId = [$userId];

        // Get all user_receiver_id values where the logged-in user is the sender and status is Complete (4)
        $connectedUserIds = DB::table('users')
            ->where('clan_id', $userId)
            ->where('status', Status::Active->value) // Status = Complete
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('id')
            ->toArray();

        // Merge the connected user IDs into the allowed IDs
        $allowedAllUsersIds = array_merge($allowedAllUsersId, $connectedUserIds);
        
        // Optional logging
        // Log::info("The allowed user IDs are: " . json_encode($allowedAllUsersIds));

        return $allowedAllUsersIds;
    }
}
