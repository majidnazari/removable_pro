<?php

namespace App\Rules\TalentDetailsScore;

use App\Models\TalentDetail;
use App\Models\TalentHeader;
use App\Models\GroupCategoryDetail;
use App\Models\GroupDetail;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\CheckUserInGroupTrait;
use Log;


class TalentDetailsScoreAuthority implements Rule
{
    use AuthUserTrait;
    use CheckUserInGroupTrait;
    private $errorMessage;
    private $personId;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->userId = $this->getUserId();
        $talentDetail = TalentDetail::find($value);

        if (!$talentDetail) {
            $this->errorMessage = "TalentDetail not found.";
            return false;
        }

        // Check if the creator_id is the same as the logged-in user
        if ($talentDetail->creator_id == $this->userId) {
            $this->errorMessage = "You cannot score your own talent.";
            return false;

        }

        // Find the related TalentHeader
        $talentHeader = TalentHeader::find($talentDetail->talent_header_id);

        if (!$talentHeader) {
            $this->errorMessage = "TalentHeader not found.";
            return false;

        }

        // Get the group_category_id from TalentHeader
        $groupCategoryId = $talentHeader->group_category_id;

        // // Step 1: Get the GroupCategoryDetails based on the `group_category_id`
        // $groupCategoryDetails = GroupCategoryDetail::whereHas('Groups', function ($query) use ($groupCategoryId) {
        //     $query->where('group_category_id', $groupCategoryId);
        // })->get();

        //       Log::info("The groupCategoryDetails are: " . json_encode($groupCategoryDetails));

        // // Step 2: Extract all group IDs
        // $groupIds = $groupCategoryDetails->pluck('group_id')->toArray();

        //       Log::info("The groupIds are: " . json_encode($groupIds));


        // // Step 3: Get all User IDs from GroupDetails with group_id in the list
        // $userIds = GroupDetail::whereIn('group_id', $groupIds)
        //     ->pluck('user_id')
        //     ->toArray();

        // // Step 4: Log the result
//       Log::info("The user IDs in the groups are: " . json_encode($userIds));

        // Check if the logged-in user exists in the group category
        // $userExistsInCategory = GroupCategoryDetail::whereHas('Groups', function ($query) use ($groupCategoryId) {
        //     $query->where('group_category_id', $groupCategoryId)
        //         ->whereHas('GroupDetails', function ($innerQuery)  {
        //             $innerQuery->where('user_id', $this->userId);
        //         });
        // })->exists();

        //       Log::info(" userExistsInCategory is :" . json_encode( $userExistsInCategory));
//       Log::info("Does the user  $this->userId exist in this category? " . ($userExistsInCategory ? 'Yes' : 'No'));
        // return false;

        $isUserInGroup = $this->isUserInGroupCategory($groupCategoryId);

        //       Log::info("the result is :" . $isUserInGroup);
        if (!$isUserInGroup) {
            $this->errorMessage = "You must be a part of the associated group to create a talent detail score.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage ?: "The participating user ID cannot be the same as the logged-in user.";
    }

}
