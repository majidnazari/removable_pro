<?php

namespace App\Traits;

use App\Models\GroupCategoryDetail;
use App\Models\Memory;
use Illuminate\Support\Facades\Auth;

trait GetsPeopleInGroups
{
    /**
     * Get people associated with a group based on groupCategoryDetails.
     *
     * @param int $groupCategoryDetailsId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPeopleInGroups($groupCategoryDetailsId)
    {
        $groupDetails = GroupCategoryDetail::with('group')
            ->where('id', $groupCategoryDetailsId)
            ->first();

        if (!$groupDetails) {
            return collect([]);
        }

        $groupIds = $groupDetails->group->pluck('id');
        $people = $groupDetails->people()
            ->whereIn('group_id', $groupIds)
            ->whereNull('deleted_at')
            ->get();

        return $people;
    }

    /**
     * Check if the current user is allowed to access a memory record.
     * Only people in the group or admin can access it.
     *
     * @param \App\Models\Memory $memory
     * @return bool
     */
    public function canAccessMemory(Memory $memory)
    {
        $user = Auth::user();
        if ($user->is_admin) {
            return true;
        }

        $groupCategoryDetailsId = $memory->group_category_details_id;
        $peopleInGroup = $this->getPeopleInGroups($groupCategoryDetailsId);

        return $peopleInGroup->contains('id', $user->id);
    }
}
