<?php

namespace App\Traits;

use App\Models\GroupCategoryDetail;
use App\Traits\AuthUserTrait;
use Illuminate\Support\Facades\Auth;

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

        // Check if the user exists in the specified group category
        return GroupCategoryDetail::whereHas('Groups', function ($query) use ($groupCategoryId, $userId) {
            $query->where('group_category_id', $groupCategoryId)
                ->whereHas('GroupDetails', function ($innerQuery) use ($userId) {
                    $innerQuery->where('user_id', $userId);
                });
        })->exists();
    }
}
