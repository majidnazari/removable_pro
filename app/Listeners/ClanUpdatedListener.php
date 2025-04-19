<?php

namespace App\Listeners;

use App\Events\ClanUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ClanUpdatedListener
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
    public function handle(ClanUpdated $event): void
    {
        //
//       Log::info("Clan ID updated for user {$event->userId}: {$event->newClanId}");
 
    }
}
