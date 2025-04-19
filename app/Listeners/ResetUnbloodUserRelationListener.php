<?php

namespace App\Listeners;

use App\Events\BloodUserRelationResetEvent;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Traits\GetAllUsersRelationInClanFromHeads;

use App\Traits\UpdateUserRelationTrait; // Import the trait

class ResetUnbloodUserRelationListener
{
    use UpdateUserRelationTrait;
    use GetAllUsersRelationInClanFromHeads;

    public function handle(BloodUserRelationResetEvent $event)
    {
        $userId = $event->userId;
        $depth = 10; // Adjust depth if needed

//       Log::info("Resetting blood_user_relation_calculated for user: $userId");

        // Get all clan users (including non-blood-related users)
        //$unbloodUserIds = $this->getAllUsersInClanFromHeads($userId, $depth);

//       Log::info("the method ResetUnbloodUserRelationListener are running");


        $unbloodUserIds=$this->getAllUsersInClanFromHeads($userId);
//       Log::info("the result of getAllUsersInClanFromHeads are ".json_encode( $unbloodUserIds));

        //$unbloodUserIds= $this->calculateUserRelationInClan();


        // Update `blood_user_relation_calculated` to false for all users
        User::whereIn('id', $unbloodUserIds)->update(['blood_user_relation_calculated' => false]);

//       Log::info("Updated unblood_user_relation_calculated to false for users: " . implode(', ', $unbloodUserIds));
    }
}
