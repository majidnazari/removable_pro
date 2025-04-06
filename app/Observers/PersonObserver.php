<?php

namespace App\Observers;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonObserver
{
    /**
     * Handle the Person "created" event.
     */
    public function created(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "updated" event.
     */
    public function updated(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "deleted" event.
     */
    public function deleted(Person $person): void
    {
        if ($person->isForceDeleting()) {
            // If it's force delete, optionally handle here if needed
            return;
        }

        $personId = $person->id;
        Log::info("PersonObserver: Soft deleting related records for person_id = {$personId}");

        // Add all related tables that reference person_id
        $tables = [
            'memories',
            'addresses',
            'favorites',
            'family_events',
            'person_details',
            'person_scores',
            'favorites',
            // add more if needed
        ];

        foreach ($tables as $table) {
            DB::table($table)
                ->where('person_id', $personId)
                ->update(['deleted_at' => now()]);

            Log::info("PersonObserver: Soft deleted records from {$table} where person_id = {$personId}");
        }
    }

    /**
     * Handle the Person "restored" event.
     */
    public function restored(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "force deleted" event.
     */
    public function forceDeleted(Person $person): void
    {
        //
    }
}
