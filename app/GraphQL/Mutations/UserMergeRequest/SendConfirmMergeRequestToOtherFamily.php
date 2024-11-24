<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Traits\PersonMergeTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GraphQL\Error\Error;
use App\GraphQL\Enums\MergeStatus;
use Exception;
use Log;

class SendConfirmMergeRequestToOtherFamily
{
    use PersonMergeTrait;

    protected $userId;

    public function __invoke($_, array $args)
    {
        return $this->resolveUserConfirmMergeRequest(null, $args);
    }

    public function resolveUserConfirmMergeRequest($rootValue, array $args)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;

        $mergeIdsSender = $args['merge_ids_sender'];        
        $mergeIdsReceiver = $args['merge_ids_receiver'];

       
        DB::beginTransaction();

        try {
            // Fetch the merge request
            $userMergeRequest = UserMergeRequest::find($args['id']);
            if (!$userMergeRequest) {
                throw new Error("UserMergeRequest-USER_MERGE_REQUEST_NOT_FOUND!");
            }

            if ($userMergeRequest->user_sender_id !== $this->userId) {
                throw new Error("UserMergeRequest-UNAUTHORIZED_ACCESS!");
            }

            $mergeIdsSender = explode(',', $mergeIdsSender );
            $mergeIdsReceiver = explode(',',  $mergeIdsReceiver);

            
            foreach (array_map(null, $mergeIdsSender, $mergeIdsReceiver) as $pair) {
                [$senderId, $receiverId] = $pair;
            
                //Log::info("The sender is: {$senderId} and the receiver is: {$receiverId}");
            
                $this->mergePersonsByIds($senderId, $receiverId, $this->userId);
            }
            
            // Update the status to Complete
            $userMergeRequest->status = MergeStatus::Complete;
            $userMergeRequest->merge_ids_sender = $args['merge_ids_sender'];
            $userMergeRequest->merge_ids_receiver = $args['merge_ids_receiver'];
            $userMergeRequest->save();

            DB::commit();

            return $userMergeRequest;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Error("Transaction failed: " . $e->getMessage());
        }
    }
}
