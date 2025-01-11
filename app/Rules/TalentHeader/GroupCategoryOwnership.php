<?php

namespace App\TalentHeader;

use App\Traits\AuthUserTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupCategory;

class GroupCategoryOwnership implements Rule
{
    use AuthUserTrait;
    public function passes($attribute, $value)
    {
        // Check if the group_category_id belongs to the logged-in user
        return GroupCategory::where('id', $value)
            ->where('user_id', $this->getUserId()) // Assuming GroupCategory has a user_id field
            ->exists();
    }

    public function message()
    {
        return 'The selected group category does not belong to you.';
    }
}
