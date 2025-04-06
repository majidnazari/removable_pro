<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Schema;

class UserObserver
{
    protected $userRelatedColumns = [
        'creator_id',
        'notifiable_id',
        'participating_user_id',
        'user_sender_id',
        'user_reciver_id',
        'editor_id',
    ];

    // Tables where you want to check only specific columns (include only)
    protected $specificTableColumns = [
        'user_merge_requests' => [ 'user_reciver_id'],
        'notifications' => ['notifiable_id'],
    ];

    // Tables where you want to exclude certain columns (ignore them)
    protected $excludedTableColumns = [
        'audits' => ['creator_id'],
    ];

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $tables = $this->getAllTablesWithUserColumns();

        foreach ($tables as $table => $columns) {
            foreach ($columns as $column) {
                Log::info("UserObserver: Table '{$table}' contains user reference in column '{$column}'.");

                if (Schema::hasColumn($table, 'deleted_at')) {
                    DB::table($table)
                        ->where($column, $user->id)
                        ->update(['deleted_at' => now()]);
                    Log::info("UserObserver: Soft deleted records in '{$table}' where '{$column}' = {$user->id}");
                } else {
                    DB::table($table)
                        ->where($column, $user->id)
                        ->delete();
                    Log::info("UserObserver: Hard deleted records in '{$table}' where '{$column}' = {$user->id}");
                }
            }
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }


    protected function getAllTablesWithUserColumns(): array
    {
        $tablesWithUserColumns = [];
        $tables = DB::select('SHOW TABLES');
        $databaseKey = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            $tableName = $table->$databaseKey;
            $columns = Schema::getColumnListing($tableName);

            // If table is in specific list, only include those columns
            if (array_key_exists($tableName, $this->specificTableColumns)) {
                $matched = array_intersect($columns, $this->specificTableColumns[$tableName]);
            } else {
                // Use default user-related columns
                $matched = array_intersect($columns, $this->userRelatedColumns);

                // Remove excluded columns for this table if defined
                if (array_key_exists($tableName, $this->excludedTableColumns)) {
                    $matched = array_diff($matched, $this->excludedTableColumns[$tableName]);
                }
            }

            if (!empty($matched)) {
                $tablesWithUserColumns[$tableName] = $matched;
            }
        }

        Log::info("UserObserver: Matched Tables => " . json_encode($tablesWithUserColumns));

        return $tablesWithUserColumns;
    }
}
