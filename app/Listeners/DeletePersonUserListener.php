<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\AuthUserTrait;
use Exception;

class DeletePersonUserListener
{
    use AuthUserTrait;

    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;
        $user = $this->getUser();
        $timestamp = Carbon::now(); // Current timestamp for soft delete

        Log::info("Initiating soft deletion for person ID: $personId by user ID: {$user->id}");

        try {
            DB::transaction(function () use ($personId, $timestamp, $user) {
                $this->softDeleteTables($user->id, $timestamp);
                $this->updateUserRelations($user->id, $timestamp);
                $this->softDeleteUser($user->id, $timestamp);
            });

            Log::info("Successfully completed soft deletion for person ID: $personId.");
        } catch (Exception $e) {
            Log::error("Soft deletion failed for person ID $personId: " . $e->getMessage());
            throw new Exception("Soft deletion failed: " . $e->getMessage());
        }
    }

    /**
     * Soft delete multiple tables related to the user.
     */
    private function softDeleteTables(int $userId, Carbon $timestamp): void
    {
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

            Log::info("Soft deleted $count records from $table for creator_id = $userId.");
        }

        // Soft delete group_details separately for user_id
        $groupDetailsCount = DB::table('group_details')
            ->where('user_id', $userId)
            ->update(['deleted_at' => $timestamp]);

        Log::info("Soft deleted $groupDetailsCount records from group_details for user_id = $userId.");
    }

    /**
     * Handle soft deletion and updates for user relations.
     */
    private function updateUserRelations(int $userId, Carbon $timestamp): void
    {
        $relatedUserIds = $this->getRelatedUserIds($userId);

        if (!empty($relatedUserIds)) {
            $updatedUsers = User::whereIn('id', $relatedUserIds)
                ->update(['blood_user_relation_calculated' => false]);

            Log::info("Updated blood_user_relation_calculated to false for $updatedUsers users.");
        }

        // Soft delete user relations
        $updatedRelations = DB::table('user_relations')
            ->where('creator_id', $userId)
            ->orWhere('related_with_user_id', $userId)
            ->update(['deleted_at' => $timestamp]);

        Log::info("Soft deleted $updatedRelations records from user_relations for user ID: $userId.");
    }

    /**
     * Get related user IDs by checking both creator_id and related_with_user_id.
     */
    private function getRelatedUserIds(int $userId): array
    {
        $createdByUserIds = DB::table('user_relations')
            ->where('creator_id', $userId)
            ->pluck('related_with_user_id');

        $relatedWithUserIds = DB::table('user_relations')
            ->where('related_with_user_id', $userId)
            ->pluck('creator_id');

        $relatedUserIds = $createdByUserIds->merge($relatedWithUserIds)->unique()->toArray();

        Log::info("Related user IDs affected: " . implode(',', $relatedUserIds));

        return $relatedUserIds;
    }

    /**
     * Soft delete the user record.
     */
    private function softDeleteUser(int $userId, Carbon $timestamp): void
    {
        $updatedUser = User::where('id', $userId)
            ->update(['deleted_at' => $timestamp]);

        Log::info("Soft deleted user with ID: $userId (Rows affected: $updatedUser).");
    }
}
