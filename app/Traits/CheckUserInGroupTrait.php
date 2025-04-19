<?php

namespace App\Traits;

use App\Models\GroupCategoryDetail;
use App\Models\GroupDetail;
use App\Traits\AuthUserTrait;
use Illuminate\Support\Facades\Auth;
use Log;

trait CheckUserInGroupTrait
{
    use AuthUserTrait;
    /**
     * Check if the logged-in user exists in the specified group category.
     *
     * @param int $groupCategoryId The ID of the group category to check.
     * @return bool True if the user exists in the group category, false otherwise.
     */
    public function isUserInGroupCategory(int $groupCategoryId): bool
    {
        $userId = $this->getUserId(); // Get the logged-in user ID

        if (!$userId) {
            return false; // User is not logged in
        }

//       Log::info("the user id is :" . $userId);
//       Log::info("the groupCategoryId id is :" . $groupCategoryId);
        // Check if the user exists in the specified group category
        //    $result= GroupCategoryDetail::whereHas('Groups', function ($query) use ($groupCategoryId, $userId) {
        //         $query->where('group_category_id', $groupCategoryId)
        //             ->whereHas('GroupDetails', function ($innerQuery) use ($userId) {
        //                 $innerQuery->where('user_id', $userId);
        //             });
        //     })->exists();
        // Extract all group_ids associated with this memory
        // $groupIds = GroupCategoryDetail::where('group_category_id', $groupCategoryId)->pluck('group_id')->toArray();

        // // Log::info("the groupIds :" . json_encode($groupIds));

        // // If no groups, return false early
        // if (empty($groupIds)) {
        //     return false;
        // }

        // // Fetch all person_ids associated with these group_ids in a single query
        // $userIdsCanSee = GroupDetail::whereIn('group_id', $groupIds)
        //     ->whereNull('deleted_at')  // Assuming there's a deleted_at column in group_details
        //     ->pluck('user_id')
        //     ->toArray();

        // // Log::info("the user id is :" . $this->getUser());
        // // Log::info("the userIdsCanSee is :" . json_encode($userIdsCanSee));

        // // Check if the logged-in user’s person_id is in the list of person_ids
        // //return in_array($user->id, $userIdsCanSee);

        // Check if the logged-in user's person_id is in the list of user_ids who can see the memory
        $userCanSee = GroupDetail::whereIn('group_id', function ($query) use ($groupCategoryId) {
            $query->select('group_id')
                ->from('group_category_details')
                ->where('group_category_id', $groupCategoryId);
        })
            ->whereNull('deleted_at')
            ->where('user_id', $userId)
            ->exists();

        return $userCanSee;
//       Log::info("the result is:" . $userCanSee);

        //return false;
    }
}
