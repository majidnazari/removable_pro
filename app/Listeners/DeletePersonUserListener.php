<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Person;
use Exception;
use Carbon\Carbon;
use App\Traits\AuthUserTrait;

class DeletePersonUserListener
{
    use AuthUserTrait;
    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;

       
        $user = $this->getUser();
        $timestamp = Carbon::now(); // Get current timestamp for soft delete

        Log::info("Soft deleting all records related to person ID: $personId.");
        Log::info("the user ID: ".$user->id);

        try {
            DB::transaction(function () use ($personId, $timestamp,$user) {
                // Tables that need soft delete (update deleted_at instead of deleting)
                $softDeleteTables = [
                    'user_details',  
                    'family_boards',
                    'family_events',
                    'favorites',
                    'groups',
                    'group_categories',
                    'group_category_details',
                    'group_details',
                    'talent_details',
                    'talent_detail_scores',
                    'talent_headers',
                ];

                foreach ($softDeleteTables as $table) {
                    $updatedCount = DB::table($table)
                        ->where('creator_id',  $user->id)
                        ->update(['deleted_at' => $timestamp]); // Soft delete

                    Log::info("Soft deleted $updatedCount records from $table where creator_id =  " .$user->id);
                }

                 // Get all related user IDs (from both creator_id and related_with_user_id)
                 $relatedUserIds = DB::table('user_relations')
                 ->where('creator_id',  $user->id)
                 ->orWhere('related_with_user_id', $user->id)
                 ->pluck('creator_id', 'related_with_user_id') // Get both columns
                 ->flatten()
                 ->unique()
                 ->toArray();

             Log::info("Related users affected: " . implode(',', $relatedUserIds));

             // Update blood_user_relation_calculated = false for all affected users
             if (!empty($relatedUserIds)) {
                 $updatedUsers = User::whereIn('id', $relatedUserIds)
                     ->update(['blood_user_relation_calculated' => false]);

                 Log::info("Updated $updatedUsers users' blood_user_relation_calculated to false.");
             }


                // Special case: user_relations (check both creator_id and related_with_user_id)
                $updatedRelations = DB::table('user_relations')
                    ->where('creator_id',  $user->id)
                    ->orWhere('related_with_user_id',  $user->id)
                    ->update(['deleted_at' => $timestamp]);

                Log::info("Soft deleted $updatedRelations records from user_relations where creator_id or related_with_user_id = $user->id.");

                // Soft delete the user (if exists)
                $updatedUser = User::where('id', $user->id)->update(['deleted_at' => $timestamp]);
                Log::info("Soft deleted user with ID:  $user->id. (Rows affected: $updatedUser)");
            });

            Log::info(" Successfully soft deleted all records related to person ID: $personId.");
        } catch (Exception $e) {
            Log::error("Soft deletion failed for person ID $personId: " . $e->getMessage());
            throw new Exception("Soft deletion failed: " . $e->getMessage());
        }
    }
}
