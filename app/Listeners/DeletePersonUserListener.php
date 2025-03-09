<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeletePersonUserListener
{
    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;
        
        Log::info("Checking if person ID $personId has a linked user account.");

        // $user = User::where('person_id', $personId)->first();
        // if ($user) {
        //     $user->delete();
        //     Log::info("Deleted user account for person ID: $personId");
        // }
    }
}
