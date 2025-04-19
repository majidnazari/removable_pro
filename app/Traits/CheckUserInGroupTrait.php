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

        $userCanSee = GroupDetail::whereIn('group_id', function ($query) use ($groupCategoryId) {
            $query->select('group_id')
                ->from('group_category_details')
                ->where('group_category_id', $groupCategoryId);
        })
            ->whereNull('deleted_at')
            ->where('user_id', $userId)
            ->exists();

        return $userCanSee;

    }
}
