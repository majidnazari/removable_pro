<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PersonDeletedEvent;
use App\Models\Favorite;

use Illuminate\Support\Facades\Log;

class DeletePersonFavoriteListener
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
        
        $deletedCount = Favorite::where('person_id', $personId)->update(['deleted_at' => now()]);

        if ($deletedCount === 0) {
            Log::warning("No Favorite records found for person ID: $personId");
        } else {
//           Log::info("Soft deleted {$deletedCount} Favorite records for person ID: $personId");
        }
    }
}
