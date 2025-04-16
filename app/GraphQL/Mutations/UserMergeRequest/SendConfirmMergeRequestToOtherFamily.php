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
use App\Traits\DuplicateCheckTrait;
use App\Traits\UpdateUserFlagTrait;
use App\GraphQL\Enums\AuthAction;
use App\Events\UpdateClanIdAfterMerge;
use App\Events\BloodUserRelationResetEvent;
use App\Events\UnbloodUserRelationResetEvent;
use App\Exceptions\CustomValidationException;


use Exception;
use Log;

class SendConfirmMergeRequestToOtherFamily
{
    use PersonMergeTrait;
    use AuthUserTrait;
    use UpdateUserFlagTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;


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
                throw new CustomValidationException("USERMERGEREQUEST-CONFIRM-MERGE-RECORD_NOT_FOUND", "درخواست ادغام کاربر. تاییددرخواست. رکورد یافت نشد", 404);


                //throw new Error("UserMergeRequest-USER_MERGE_REQUEST_NOT_FOUND!");
            }
            $this->checkDuplicate(
                new UserMergeRequest(),
                $args,
                ['id', 'editor_id', 'created_at', 'updated_at'],
                $args['id']
            );

            if ($userMergeRequest->user_sender_id !== $this->user->id) {
                throw new CustomValidationException("USERMERGEREQUEST-CONFIRM-MERGE-UNAUTHORIZED_ACCESS!", "ارسال درخواست مرج کاربر. تاییددرخواست . دسترسی غیر مجاز", 403);

                //throw new Error("UserMergeRequest-UNAUTHORIZED_ACCESS!");
            }

            $mergeIdsSender = explode(',', $mergeIdsSender);
            $mergeIdsReceiver = explode(',', $mergeIdsReceiver);


            foreach (array_map(null, $mergeIdsSender, $mergeIdsReceiver) as $pair) {
                [$senderId, $receiverId] = $pair;

                // Log::info("before mergePersonsByIds is: {$senderId} and the receiver is: {$receiverId}");

                $this->mergePersonsByIds($senderId, $receiverId, $this->user->id);
            }

            // Update the status to Complete
            $userMergeRequest->status = MergeStatus::Complete;
            $userMergeRequest->merge_ids_sender = $args['merge_ids_sender'];
            $userMergeRequest->merge_ids_receiver = $args['merge_ids_receiver'];
            $userMergeRequest->save();
            // Dispatch the event to update the clan_id after the merge
            event(new UpdateClanIdAfterMerge($userMergeRequest->user_sender_id, $userMergeRequest->user_receiver_id));

            // Dispatch blood user event for sender
            event(new BloodUserRelationResetEvent($userMergeRequest->user_sender_id));

            // Dispatch unblood user event for receiver
            event(new UnbloodUserRelationResetEvent($userMergeRequest->user_receiver_id));

            // $this->updateUserCalculationFlag($userMergeRequest->user_sender_id, false);
            // $this->updateUserCalculationFlag($userMergeRequest->user_receiver_id, false);

            DB::commit();

            return $userMergeRequest;
        } catch (CustomValidationException $e) {
            DB::rollBack();

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            DB::rollBack();
            throw new Error("Transaction failed: " . $e->getMessage());
        }
    }
}
