<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status; // Make sure the MergeStatus Enum is imported

use App\Traits\AuthUserTrait;

trait GetAllowedAllUsersInClan
{
    use AuthUserTrait;
    /**
     * Get allowed creator IDs for a user, including their connected users where the merge status is "Complete".
     *
     * @param  int  $userId
     * @return array
     */
    protected function getAllowedUserIds(int $userId = null): array
    {
        if ($userId === null) {
            $userId = $this->getUserId();
        }
        // Get the clan_id of the given user
        $clanId = DB::table('users')
            ->where('id', $userId)
            ->whereNull('deleted_at')
            ->value('clan_id');

        if (!$clanId) {
            // If no clan_id is found, return only the user's ID
            return [$userId];
        }

        // Fetch all user IDs that share the same clan_id
        $userIds = DB::table('users')
            ->where('clan_id', $clanId)
            ->where('status', Status::Active->value) // Status must be Active
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('id')
            ->toArray();

        // Return the list of user IDs
        return $userIds;
    }
}
