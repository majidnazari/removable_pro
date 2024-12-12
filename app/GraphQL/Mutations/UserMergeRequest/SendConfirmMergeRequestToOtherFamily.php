<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Traits\PersonMergeTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GraphQL\Error\Error;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


use Exception;
use Log;

class SendConfirmMergeRequestToOtherFamily
{
    use PersonMergeTrait;
    use AuthUserTrait;
    use AuthorizesMutation;


    protected $userId;

    public function __invoke($_, array $args)
    {
        return $this->resolveUserConfirmMergeRequest(null, $args);
    }

    public function resolveUserConfirmMergeRequest($rootValue, array $args)
    {
        $this->user = $this->getUser();
    
       $this->userAccessibility(UserMergeRequest::class, AuthAction::Delete, $args);

        $mergeIdsSender = $args['merge_ids_sender'];        
        $mergeIdsReceiver = $args['merge_ids_receiver'];
       
        DB::beginTransaction();

        try {
            // Fetch the merge request
            $userMergeRequest = UserMergeRequest::find($args['id']);
            if (!$userMergeRequest) {
                throw new Error("UserMergeRequest-USER_MERGE_REQUEST_NOT_FOUND!");
            }

            if ($userMergeRequest->user_sender_id !== $this->user->id) {
                throw new Error("UserMergeRequest-UNAUTHORIZED_ACCESS!");
            }

            $mergeIdsSender = explode(',', $mergeIdsSender );
            $mergeIdsReceiver = explode(',',  $mergeIdsReceiver);

            
            foreach (array_map(null, $mergeIdsSender, $mergeIdsReceiver) as $pair) {
                [$senderId, $receiverId] = $pair;
            
                Log::info("before mergePersonsByIds is: {$senderId} and the receiver is: {$receiverId}");
            
                $this->mergePersonsByIds($senderId, $receiverId, $this->user->id);
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
