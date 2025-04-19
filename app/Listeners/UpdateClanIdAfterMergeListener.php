<?php

namespace App\Listeners;

use App\Events\UpdateClanIdAfterMerge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\MergeStatus;

use Log;

class UpdateClanIdAfterMergeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateClanIdAfterMerge $event): void
    {

//       Log::info("the sender is :" . $event->senderId);
//       Log::info("the receiver is :" . $event->receiverId);

        // Get the sender and receiver user IDs from the event
        $senderId = $event->senderId;
        $receiverId = $event->receiverId;

        // Fetch the sender and receiver users to get the new clan_id
        $sender = DB::table('users')->where('id', $senderId)->first();
        $receiver = DB::table('users')->where('id', $receiverId)->first();

//       Log::info("sender is  :" . json_encode($sender));
//       Log::info("receiver :" . json_encode($receiver));

        // Ensure the sender exists and has a clan_id
        if ($sender && $sender->clan_id) {
            // Get the new clan_id from the sender
            $clanId = $sender->clan_id;

            // Fetch all users with the receiver's clan_id that need to be updated
            $usersToUpdate = DB::table('users')
                ->where('clan_id', $receiver->clan_id) // Find all users with the receiver's clan_id
                ->get();
//           Log::info("all of users must change are :" . json_encode($usersToUpdate));
            // Update the clan_id for all these users to the sender's clan_id
            foreach ($usersToUpdate as $user) {
                DB::table('users')
                    ->where('id', $user->id) // Update each user's clan_id
                    ->update(['clan_id' => $clanId]); // Set the new clan_id
            }
        }
    }

}
