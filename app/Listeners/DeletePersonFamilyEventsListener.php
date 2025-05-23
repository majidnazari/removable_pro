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

        $deletedCount = FamilyEvent::where('person_id', $personId)->update(['deleted_at' => now()]);

        if ($deletedCount === 0) {
            Log::warning("No FamilyEvent records found for person ID: $personId");
        } else {
            //           Log::info("Soft deleted {$deletedCount} FamilyEvent records for person ID: $personId");
        }
    }
}
