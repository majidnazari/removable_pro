<?php

namespace App\Traits;

use App\Models\GroupCategoryDetail;
use App\Models\Memory;
use App\Models\GroupDetail;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use Log;

trait GetsPeopleInGroups
{
    use AuthUserTrait;
    protected $user;
    /**
     * Get people associated with a group based on groupCategoryDetails.
     *
     * @param int $groupCategoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // public function getPeopleInGroups($groupCategoryId)
    // {
    //     // Get all GroupCategoryDetails for the given groupCategoryId and creator_id
    //     $groupDetails = GroupCategoryDetail::where('group_category_id', $groupCategoryId)
    //         ->where('creator_id', $this->getUserId())
    //         ->get();

//       Log::info("inside getpeople groupDetails: " . json_encode($groupDetails));

    //     if ($groupDetails->isEmpty()) {
    //         return collect([]);
    //     }

    //     // Get all group_ids in one go
    //     $groupIds = $groupDetails->pluck('group_id')->toArray();

    //     // Fetch all person_ids in one query for the retrieved group_ids
    //     $personIdsByGroup = \DB::table('group_details')
    //         ->whereIn('group_id', $groupIds)
    //         ->whereNull('deleted_at')  // Assuming there's a deleted_at column
    //         ->get(['group_id', 'person_id']);  // Fetch group_id and person_id

    //     // Group person_ids by group_id
    //     $groupedPersonIds = $personIdsByGroup->groupBy('group_id')->map(function ($group) {
    //         return $group->pluck('person_id');
    //     });

    //     // Collect person_ids for each group_detail
    //     $peopleIds = $groupDetails->mapWithKeys(function ($groupDetail) use ($groupedPersonIds) {
    //         return [
    //             $groupDetail->id => $groupedPersonIds->get($groupDetail->group_id, collect([]))
    //         ];
    //     });

//       Log::info("inside getpeople peopleIds: " . json_encode($peopleIds));

    //     return $peopleIds;
    // }


    /**
     * Check if the current user is allowed to access a memory record.
     * Only people in the group or admin can access it.
     *
     * @param \App\Models\Memory $memory
     * @return bool
     */
    public function canAccessMemory(Memory $memory)
    {
        $this->user = $this->getUser();
//      Log::info("the memory is :" . ($memory));

        // $groupCategoryId = $memory->group_category_id;

//       Log::info("the groupCategoryId is :" . ($groupCategoryId));

        // $peopleInGroup = $this->getPeopleInGroups($groupCategoryId);

//       Log::info("the people in groups are:" . json_encode($peopleInGroup));
//       Log::info("the the user can see this memory:" . $peopleInGroup->contains('id', $user->id));
        // return $peopleInGroup->contains('id', $user->id);

        // Admin or Supporter: Always return true for full access
        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            return true;
        }

        // If the memory's creator_id matches the logged-in user, allow access
        if ($memory->creator_id == $this->user->id) {
            return true;
        }

        // Now, check if the memory is associated with a group that the user is allowed to access
        $groupPeople= $this->hasAccessToGroup($memory);
        return $groupPeople;
    }

    private function hasAccessToGroup($memory)
    {
        // Get the logged-in user's ID
        $user =  $this->getUser();

        // Get all groupCategoryDetails related to the memory
        $groupCategoryId = $memory->group_category_id;

//      Log::info("the memory :" . json_encode($memory));
//      Log::info("the groupCategoryDetailsis :" . $groupCategoryId);


        // Extract all group_ids associated with this memory
        $groupIds = GroupCategoryDetail::where('group_category_id',$groupCategoryId)->pluck('group_id')->toArray();

//      Log::info("the groupIds :" . json_encode($groupIds));

        // If no groups, return false early
        if (empty($groupIds)) {
            return false;
        }

        // Fetch all person_ids associated with these group_ids in a single query
        $userIdsCanSee = GroupDetail::whereIn('group_id', $groupIds)
            ->whereNull('deleted_at')  // Assuming there's a deleted_at column in group_details
            ->pluck('user_id')
            ->toArray();

//      Log::info("the user id is :" . $this->getUser());
//      Log::info("the userIdsCanSee is :" . json_encode($userIdsCanSee));

        // Check if the logged-in user’s person_id is in the list of person_ids
        return in_array($user->id, $userIdsCanSee);
    }

}
