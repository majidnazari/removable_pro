<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;

trait UpdateUserFlagTrait
{
    /**
     * Update the user's blood_user_relation_calculated flag.
     *
     * @param int $userId
     * @return void
     */
    public function updateUserCalculationFlag($userId, $flag)
    {
        User::where('id', $userId)->update(['blood_user_relation_calculated' => $flag]);
        $user = User::where('id', $userId)->first();

        Log::info("Updated blood_user_relation_calculated flag for user ID: {$userId} and user is :" . json_encode($user));
    }
}
