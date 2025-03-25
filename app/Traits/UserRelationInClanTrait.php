<?php

namespace App\Traits;

use App\Models\UserRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

trait UserRelationInClanTrait
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllBloodUsersRelationInClanFromHeads;

    public function calculateUserRelationInClan($depth = 3)
    {
        $user = $this->getUser();

        // If blood_user_relation_calculated is true, fetch from user_relations directly
        if ($user->blood_user_relation_calculated) {
            return $this->getBloodUserRelation($user->id);
        }

        // Get all blood-related users in clan
        $userIds = $this->getAllBloodUsersInClanFromHeads($user->id, $depth);

        // If there are blood relations and they haven't been calculated, process them
        if (!empty($userIds)) {
            DB::beginTransaction();

            try {
                // Fetch the IDs of the relations to be deleted
                $relationsToDelete = UserRelation::where('creator_id', $user->id)->pluck('id');

                // Log the IDs of the relations to be deleted
                Log::info("Deleting user relations for user {$user->id}. IDs to be deleted: " . $relationsToDelete->implode(', '));

                // Delete all existing relations for this user before inserting new ones
                UserRelation::where('creator_id', $user->id)->delete();

                // Log the deletion action
                Log::info("Deleted relations for user {$user->id}.");

                // Insert new relations
                $this->createUserRelationsBulk($user->id, $userIds);

                // Log the creation of new relations
                Log::info("Created new user relations for user {$user->id} with related users: " . implode(', ', $userIds));

                // Update blood relation flag
                $this->updateBloodRelation($user->id);

                // Log the update of blood relation flag
                Log::info("Updated blood_user_relation_calculated flag for user {$user->id}.");

                DB::commit();
                Log::info("User {$user->id} blood relations updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Transaction failed for user {$user->id}: " . $e->getMessage());
            }
        }

        return $this->getBloodUserRelation($user->id);
    }

    private function createUserRelationsBulk($user_id, array $related_user_ids)
    {
        $relations = array_map(fn($id) => [
            'creator_id' => $user_id,
            'related_with_user_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ], $related_user_ids);

        if (!empty($relations)) {
            // Log the bulk creation of relations
            Log::info("Bulk creating user relations for user {$user_id} with related users: " . implode(', ', $related_user_ids));

            DB::table('user_relations')->insert($relations);

            // Log after successful bulk creation
            Log::info("Bulk creation of user relations for user {$user_id} completed.");
        }
    }

    private function updateBloodRelation($user_id)
    {
        User::where('id', $user_id)->update(['blood_user_relation_calculated' => true]);

        // Log the update of blood relation flag
        Log::info("Blood relation flag updated for user {$user_id}.");
    }

    public function getBloodUserRelation($user_id)
    {
        return UserRelation::where('creator_id', $user_id)->pluck('related_with_user_id')->toArray();
    }

    public function getBloodUserOtherHandRelation($user_id)
    {
        return UserRelation::where('related_with_user_id', $user_id)->pluck('creator_id')->toArray();
    }
}
