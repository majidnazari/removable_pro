<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status; // Make sure the MergeStatus Enum is imported

use App\Traits\AuthUserTrait;

trait GetAllowedAllUsersInRelation
{
    use AuthUserTrait;
    /**
     * Get allowed creator IDs for a user, including their connected users where the merge status is "Complete".
     *
     * @param  int  $userId
     * @return array
     */
    protected function getAllowedUserIds(?int $userId = null): array
    {
        if ($userId === null) {
            $userId = $this->getUserId();
        }

        // Fetch all user IDs that share the user relation
        $userIds = DB::table('user_relations')
            ->where('creator_id', $userId)
            //->where('status', Status::Active->value) // Status must be Active
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('related_with_user_id')
            ->toArray();

        // Return the list of user IDs
        return $userIds;
    }
}
