<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PersonDeletedEvent;
use App\Models\FamilyEvent;

use Illuminate\Support\Facades\Log;

class DeletePersonFamilyEventsListener
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
        //FamilyEvent::where('person_id', $personId)->delete();
        Log::info("Deleted family events related to person ID: $personId");
    }
}
