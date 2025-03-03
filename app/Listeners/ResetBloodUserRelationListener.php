<?php

namespace App\Listeners;

use App\Events\BloodUserRelationResetEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ResetBloodUserRelationListener
{
    public function handle(BloodUserRelationResetEvent $event)
    {
        $userId = $event->userId;
        $depth = 10; // Adjust depth if needed

        Log::info("Resetting blood_user_relation_calculated for user: $userId");

        // Get all blood-related users
        $bloodUserIds = app()->call('App\Traits\PersonMergeTrait@getAllBloodUsersInClanFromHeads', [
            'personId' => $userId,
            'depth' => $depth
        ]);

        // Get all clan users (including non-blood-related users)
        // $unbloodUserIds = app()->call('App\Traits\PersonMergeTrait@getAllUsersInClanFromHeads', [
        //     'personId' => $userId,
        //     'depth' => $depth
        // ]);

        // Merge both arrays and make them unique
       // $allUserIds = array_unique(array_merge($bloodUserIds, $unbloodUserIds));

        // Update `blood_user_relation_calculated` to false for all users
        User::whereIn('id',  $bloodUserIds)->update(['blood_user_relation_calculated' => false]);

        Log::info("Updated blood_user_relation_calculated to false for users: " . implode(', ',  $bloodUserIds));
    }
}
