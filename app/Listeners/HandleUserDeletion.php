<?php

namespace App\Listeners;

use App\Events\DeleteUserFromAllRelations;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HandleUserDeletion
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
    public function handle(DeleteUserFromAllRelations $event): void
    {
        $userId = $event->userId;
        $timestamp = Carbon::now();
        
        $tables = [
            'addresses', 'memories', 'user_details', 'family_boards',
            'family_events', 'favorites', 'groups', 'group_categories',
            'group_category_details', 'group_details', 'talent_details',
            'talent_detail_scores', 'talent_headers',
        ];

        foreach ($tables as $table) {
            $count = DB::table($table)
                ->where('creator_id', $userId)
                ->update(['deleted_at' => $timestamp]);

//           Log::info("Soft deleted $count records from $table for creator_id = $userId. for table {$table}");
        }

        // Soft delete group_details separately for user_id
        $groupDetailsCount = DB::table('group_details')
            ->where('user_id', $userId)
            ->update(['deleted_at' => $timestamp]);

//       Log::info("Soft deleted $groupDetailsCount records from group_details for user_id = $userId.");
    
    }
}
