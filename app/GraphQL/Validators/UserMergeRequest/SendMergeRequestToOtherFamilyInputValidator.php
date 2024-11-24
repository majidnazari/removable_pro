<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;

use App\Rules\UserMergeRequest\MergeIdsAreDifferent;
use App\Rules\UserMergeRequest\MergeNoDuplicateIds;
use App\Rules\UserMergeRequest\MergeValidateNodeIdsInMergeIds;
use App\Rules\UserMergeRequest\MergeValidateMergeIdsWithinActiveRelations;
use App\Rules\UserMergeRequest\MergeValidateMergeIdsSender;
use App\Rules\UserMergeRequest\MergeValidateMergeIdReceiver;
use App\Rules\UserMergeRequest\MergeEqualCountIds;
use App\Rules\UserMergeRequest\MergeRequestIdExists;
use App\Rules\UserMergeRequest\PreventChangeCompleteStatus;

use Illuminate\Support\Facades\Auth;

use Exception;
use Log;

class SendMergeRequestToOtherFamilyInputValidator extends Validator
{
    protected $userId;

    public function __construct()
    {
        // Ensure a user is authenticated
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        Log::info("the user id is:". $this->userId );
    }

    /**
     * Return the validation rules for the input.
     */
    public function rules(): array
    {
        $mergeIdsSender = $this->arg('merge_ids_sender');
        $userMergeRequestId = $this->arg('id');
        $mergeIdsReceiver = $this->arg('merge_ids_receiver');
        return [
            'id' => [
                'required',
                new MergeRequestIdExists(), 
                new MergeValidateNodeIdsInMergeIds($userMergeRequestId, $mergeIdsSender, $mergeIdsReceiver), // Validate sender and receiver node IDs
                new PreventChangeCompleteStatus($this->arg('id')),
            ],
            'merge_ids_sender' => [
                'required',
                'string',
                new MergeIdsAreDifferent($mergeIdsReceiver),
                new MergeNoDuplicateIds(),
                new MergeEqualCountIds($mergeIdsReceiver),
                //new MergeValidateMergeIdsWithinActiveRelations($this->userId),
                new MergeValidateMergeIdsSender($this->userId),
            ],
            'merge_ids_receiver' => [
                'required',
                'string',
                new MergeNoDuplicateIds(),
                // new MergeValidateMergeIdsWithinActiveRelations($this->userId),
                new MergeValidateMergeIdReceiver($this->userId),

            ],

        ];
    }
}
