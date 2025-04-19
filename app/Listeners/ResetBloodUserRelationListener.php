<?php

namespace App\Listeners;

use App\Events\BloodUserRelationResetEvent;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Traits\GetAllBloodUsersRelationInClanFromHeads; // Import the trait

class ResetBloodUserRelationListener
{
    use GetAllBloodUsersRelationInClanFromHeads; // Use the trait inside the listener

    public function handle(BloodUserRelationResetEvent $event)
    {
        $userId = $event->userId;
        $depth = 10; // Adjust depth if needed

        //       Log::info("Resetting blood_user_relation_calculated for user: $userId");

        // Use the trait method directly
        $bloodUserIds = $this->getAllBloodUsersInClanFromHeads($userId, $depth);

        // Update `blood_user_relation_calculated` to false for all users
        User::whereIn('id', $bloodUserIds)->update(['blood_user_relation_calculated' => false]);

        //       Log::info("Updated blood_user_relation_calculated to false for users: " . implode(', ', $bloodUserIds));
    }
}
