<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PersonDeletedEvent;
use App\Models\TalentHeader;

use Illuminate\Support\Facades\Log;

class DeletePersonTalentHeaderListener
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
    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;

        // If no relationships exist, proceed with deleting related events
        //TalentHeader::where('person_id', $personId)->delete();
        Log::info("Deleted TalentHeader related to person ID: $personId");
    }
}
