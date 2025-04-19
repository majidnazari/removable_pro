<?php

namespace App\Traits;

use App\Models\UserRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Exceptions\CustomValidationException;


trait UpdateUserRelationTrait
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllBloodUsersRelationInClanFromHeads;

    public function calculateUserRelationInClan(array $allUserIds)
    {
        $user = $this->getUser();

        DB::beginTransaction();

        try {
            // Always update the database first
            $this->syncUserRelationsWithDatabase($user->id, $allUserIds);

            // Fetch updated blood user relations
            $updatedRelations = $this->getAllUsersRelatedToCreator($user->id);

            DB::commit();
            //           Log::info("User {$user->id} blood relations updated successfully.");

            return $updatedRelations;

        } catch (CustomValidationException $e) {
            Log::error("Failed to update user calculation flags: " . $e->getMessage());
            DB::rollBack();

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), statusCode: $e->getStatusCode());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed for user {$user->id}: " . $e->getMessage());
            return [];
        }
    }
    protected function syncUserRelationsWithDatabase($creatorId, array $allUserIds)
    {
        // Get current relations from the database
        $existingRelations = UserRelation::where('creator_id', $creatorId)
            ->pluck('related_with_user_id')
            ->toArray();

        $usersToAdd = array_diff($allUserIds, $existingRelations);
        $usersToRemove = array_diff($existingRelations, $allUserIds);

        if (!empty($usersToRemove)) {


            UserRelation::where('creator_id', $creatorId)
                ->whereIn('related_with_user_id', $usersToRemove)
                ->delete();

        }

        // Add new relations
        if (!empty($usersToAdd)) {
            $this->createUserRelationsBulk($creatorId, $usersToAdd);
        }

        // Update blood relation flag
        $this->updateUserFlag($creatorId);
    }
    private function createUserRelationsBulk($user_id, array $related_user_ids)
    {
        if (empty($related_user_ids)) {
            return;
        }

        // Get existing relations to avoid duplicates
        $existingRelations = DB::table('user_relations')
            ->where('creator_id', $user_id)
            ->whereIn('related_with_user_id', $related_user_ids)
            ->pluck('related_with_user_id')
            ->toArray();

        // Filter out duplicates
        $newRelations = array_filter($related_user_ids, function ($id) use ($existingRelations) {
            return !in_array($id, $existingRelations);
        });

        // Prepare new relations for insertion
        $relations = array_map(function ($id) use ($user_id) {
            return [
                'creator_id' => $user_id,
                'related_with_user_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $newRelations);

        //       Log::info("The user relations to be created: " . json_encode($relations));

        if (!empty($relations)) {
            DB::table('user_relations')->insertOrIgnore($relations);
        }
    }

    private function getAllUsersRelatedToCreator($creatorId)
    {
        $userRelated = UserRelation::where('creator_id', $creatorId)->pluck('related_with_user_id')->toArray();

        //       Log::info("getAllUsersRelatedToCreator for user {$creatorId}.".json_encode($userRelated));

        return $userRelated;
    }
    private function getAllCreatorExistIn($creatorId)
    {
        $userRelated = UserRelation::where('related_with_user_id', $creatorId)->pluck('creator_id')->toArray();

        //       Log::info("getAllCreatorExistIn for user {$creatorId}.".json_encode($userRelated));

        return $userRelated;
    }
    protected function updateUserFlag($userId)
    {
        // Check if these users have family ties (e.g., shared parents/children)
        User::where('id', $userId)->update(['blood_user_relation_calculated' => true]);
        //       Log::info("Updated user  for creator_id: {$userId}");

    }


}
