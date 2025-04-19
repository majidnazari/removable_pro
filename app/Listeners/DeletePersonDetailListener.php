<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PersonDeletedEvent;
use App\Models\PersonDetail;

use Illuminate\Support\Facades\Log;

class DeletePersonDetailListener
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

        $deletedCount = PersonDetail::where('person_id', $personId)->update(['deleted_at' => now()]);

        if ($deletedCount === 0) {
            Log::warning("No PersonDetail records found for person ID: $personId");
        } else {
//           Log::info("Soft deleted {$deletedCount} PersonDetail records for person ID: $personId");
        }
    }
}
