<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomValidationException;


trait UpdateUserFlagTrait
{
    /**
     * Update the blood_user_relation_calculated flag for:
     * 1. The specified user
     * 2. All users related through user_relations where creator_id = user_id
     *
     * @param int $userId
     * @param bool $flag
     * @return void
     */
    public function updateUserCalculationFlag($userId, $flag)
    {
        
        // Start transaction for atomic operation
        DB::beginTransaction();
        
        try {
            // 1. Update the specified user
            User::where('id', $userId)->update(['blood_user_relation_calculated' => $flag]);
            
            // 2. Get all related user IDs from user_relations table
            $relatedUserIds = DB::table('user_relations')
                ->where('creator_id', $userId)
                ->whereNull('deleted_at')
                ->pluck('related_with_user_id');
            
            Log::error("UpdateUserFlagTrait : relatedUserIds" . json_encode($relatedUserIds));

            // 3. Update all related users (if any exist)
            if (!empty($relatedUserIds)) {
                User::whereIn('id', $relatedUserIds)
                    ->update(['blood_user_relation_calculated' => $flag]);
            }
            
            // Log the updated users
            $updatedUsers = User::where('id', $userId)
                ->orWhereIn('id', $relatedUserIds)
                ->whereNull('deleted_at')
                ->get();
                
            Log::info("Updated blood_user_relation_calculated flag to " . ($flag ? 'true' : 'false') . 
                     " for user ID: {$userId} and related users: " . 
                     json_encode($updatedUsers->pluck('id')));
            
            DB::commit();
            
       
        } catch (CustomValidationException $e) {
            Log::error("Failed to update user calculation flags: " . $e->getMessage());
            DB::rollBack();

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), statusCode: $e->getStatusCode());
            
        }  catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update user calculation flags: " . $e->getMessage());
            throw $e;
        }
    }
}