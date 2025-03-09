<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import User model
use Exception;

class DeletePersonUserListener
{
    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;
        
        Log::info("Deleting all records related to person ID: $personId.");

        try {
            DB::transaction(function () use ($personId) {
                // Tables that only need creator_id check
                $tables = [
                    'user_details',  
                    'family_boards',
                    'family_events',
                    'events',
                    'favorites',
                    'groups',
                    'group_categories',
                    'group_category_details',
                    'group_details',
                    'talent_details',
                    'talent_detail_scores',
                    'talent_headers',
                    // Add more tables here if needed
                ];

                foreach ($tables as $table) {
                    $deletedCount = DB::table($table)->where('creator_id', $personId)->delete();
                    Log::info("Deleted $deletedCount records from $table where creator_id = $personId.");
                }

                // Special case: user_relations (check both creator_id and related_with_user_id)
                $deletedRelations = DB::table('user_relations')
                    ->where('creator_id', $personId)
                    ->orWhere('related_with_user_id', $personId)
                    ->delete();

                Log::info("Deleted $deletedRelations records from user_relations where creator_id or related_with_user_id = $personId.");

                // Delete the user (if exists)
                $deletedUser = User::where('id', $personId)->delete();
                Log::info("Deleted user with ID: $personId. (Rows affected: $deletedUser)");
            });

            Log::info(" Successfully deleted all records related to person ID: $personId.");
        } catch (Exception $e) {
            Log::error(" Deletion failed for person ID $personId: " . $e->getMessage());
            throw new Exception("Deletion failed: " . $e->getMessage());
        }
    }
}
