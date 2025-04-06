<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UserObserver
{
    // Define per-table columns to check for user references
    protected $userRelatedColumns = [
        //'user_merge_requests' => ['user_sender_id'], // ONLY check these columns in this table
        //'notifications' => ['notifiable_id'],
        //'audits' => ['editor_id'], // Only include editor_id (creator_id excluded)
        'addresses' => ['creator_id', 'editor_id'],
        'events' => ['creator_id'],
        'family_boards' => ['creator_id'],
        'family_events' => ['creator_id'],
        'favorites' => ['creator_id'],
        'groups' => ['creator_id'],
        'group_categories' => ['creator_id'],
        'group_category_details' => ['creator_id'],
        'group_details' => ['creator_id','user_id'],
        'memories' => ['creator_id'],
        'people' => ['creator_id'],
        'person_children' => ['creator_id'],
        'person_marriages' => ['creator_id'],
        'person_scores' => ['creator_id'],
        'talent_details' => ['creator_id'],
        'talent_detail_scores' => ['creator_id'],
        'talent_headers' => ['creator_id'],
        'user_details' => ['creator_id'],
        'user_relations' => ['creator_id'],
        'user_merge_requests' => ['creator_id', 'user_receiver_id'],
        //'tasks' => ['creator_id', 'user_id'],
        // Add more tables here as needed
    ];

    // Excluded tables entirely (won’t be checked at all)
    protected $exceptTables = [
        'migrations',
        'failed_jobs',
    ];

    public function created(User $user): void {}
    public function updated(User $user): void {}
    public function restored(User $user): void {}
    public function forceDeleted(User $user): void {}

    public function deleted(User $user): void
    {
        $tables = $this->getAllTablesWithUserColumns();

        foreach ($tables as $table => $columns) {
            Log::info("UserObserver: Processing table '{$table}' with columns: " . implode(', ', $columns));

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    Log::warning("UserObserver: Column '{$column}' does not exist in table '{$table}'. Skipping.");
                    continue;
                }

                if (Schema::hasColumn($table, 'deleted_at')) {
                    DB::table($table)
                        ->where($column, $user->id)
                        ->update(['deleted_at' => now()]);
                    Log::info("UserObserver: Soft deleted from '{$table}' where '{$column}' = {$user->id}");
                } else {
                    DB::table($table)
                        ->where($column, $user->id)
                        ->delete();
                    Log::info("UserObserver: Hard deleted from '{$table}' where '{$column}' = {$user->id}");
                }
            }
        }
    }

    protected function getAllTablesWithUserColumns(): array
    {
        $tablesWithUserColumns = [];
        $tables = DB::select('SHOW TABLES');
        $dbKey = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            $tableName = $table->$dbKey;

            if (in_array($tableName, $this->exceptTables)) {
                Log::info("UserObserver: Skipping excluded table '{$tableName}'.");
                continue;
            }

            if (!array_key_exists($tableName, $this->userRelatedColumns)) {
                continue; // Only process tables explicitly listed
            }

            $columnsInTable = Schema::getColumnListing($tableName);
            $userColumns = $this->userRelatedColumns[$tableName];
            $matched = array_intersect($columnsInTable, $userColumns);

            if (!empty($matched)) {
                $tablesWithUserColumns[$tableName] = $matched;
            }
        }

        Log::info("UserObserver: Final matched table/column list => " . json_encode($tablesWithUserColumns));
        return $tablesWithUserColumns;
    }
}
