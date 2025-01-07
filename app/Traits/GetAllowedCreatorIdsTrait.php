<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\MergeStatus; // Make sure the MergeStatus Enum is imported

trait GetAllowedCreatorIdsTrait
{
    /**
     * Get allowed creator IDs for a user, including their connected users where the merge status is "Complete".
     *
     * @param  int  $userId
     * @return array
     */
    protected function getAllowedCreatorIds(int $userId): array
    {
        // Start with the logged-in user ID
        $allowedCreatorIds = [$userId];

        // Get all user_receiver_id values where the logged-in user is the sender and status is Complete (4)
        $connectedUserIds = DB::table('user_merge_requests')
            ->where('user_sender_id', $userId)
            ->where('status', MergeStatus::Complete->value) // Status = Complete
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('user_receiver_id')
            ->toArray();

        // Merge the connected user IDs into the allowed IDs
        $allowedCreatorIds = array_merge($allowedCreatorIds, $connectedUserIds);
        
        // Optional logging
        // Log::info("The allowed user IDs are: " . json_encode($allowedCreatorIds));

        return $allowedCreatorIds;
    }
}
