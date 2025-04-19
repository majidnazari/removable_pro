<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PersonDeletedEvent;
use App\Models\PersonScore;

use Illuminate\Support\Facades\Log;

class DeletePersonScoreListener
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

        // Soft delete all records in a single query
        $deletedCount = PersonScore::where('person_id', $personId)->update(['deleted_at' => now()]);

        if ($deletedCount === 0) {
            Log::warning("No PersonScore records found for person ID: $personId");
        } else {
//           Log::info("Soft deleted {$deletedCount} PersonScore records for person ID: $personId");
        }
    }
}
