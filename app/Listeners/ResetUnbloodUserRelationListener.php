<?php

namespace App\Listeners;

use App\Events\BloodUserRelationResetEvent;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Traits\GetAllUsersRelationInClanFromHeads; // Import the trait

class ResetUnbloodUserRelationListener
{
    use GetAllUsersRelationInClanFromHeads; // Use the trait inside the listener

    public function handle(BloodUserRelationResetEvent $event)
    {
        $userId = $event->userId;
        $depth = 10; // Adjust depth if needed

        Log::info("Resetting blood_user_relation_calculated for user: $userId");

        // Get all clan users (including non-blood-related users)
        $unbloodUserIds = $this->getAllUsersInClanFromHeads($userId, $depth);

        // Update `blood_user_relation_calculated` to false for all users
        User::whereIn('id', $unbloodUserIds)->update(['blood_user_relation_calculated' => false]);

        Log::info("Updated unblood_user_relation_calculated to false for users: " . implode(', ', $unbloodUserIds));
    }
}
