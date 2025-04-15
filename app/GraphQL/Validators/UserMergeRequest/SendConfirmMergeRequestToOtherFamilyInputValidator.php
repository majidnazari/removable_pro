<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;

use App\Rules\UserMergeRequest\MergeIdsAreDifferent;
use App\Rules\UserMergeRequest\MergeNoDuplicateIds;
use App\Rules\UserMergeRequest\MergeValidateNodeIdsInMergeIds;
use App\Rules\UserMergeRequest\MergeValidateMergeIdsSender;
use App\Rules\UserMergeRequest\MergeValidateMergeIdReceiver;
use App\Rules\UserMergeRequest\MergeEqualCountIds;
use App\Rules\UserMergeRequest\MergeRequestIdExists;
use App\Rules\UserMergeRequest\MergeValidateAllStatusesAreActive;
use App\Rules\UserMergeRequest\MergeEnsureNotEmpty;
use App\Rules\UserMergeRequest\PreventChangeCompleteStatus;
use App\Exceptions\CustomValidationException;


use Illuminate\Support\Facades\Auth;

use Exception;

class SendConfirmMergeRequestToOtherFamilyInputValidator extends Validator
{
    protected $userId;

    public function __construct()
    {
        // Ensure a user is authenticated
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new CustomValidationException("Authentication required. No user is currently logged in.", "احراز هویت لازم است. هیچ کاربری در حال حاضر وارد نشده است.", 403);

            //throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
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
                new MergeValidateAllStatusesAreActive($userMergeRequestId, $this->userId),
                new PreventChangeCompleteStatus($this->arg('id')),
            ],
            'merge_ids_sender' => [
                'required',
                'string',
                new MergeIdsAreDifferent($mergeIdsReceiver),
                new MergeNoDuplicateIds(),
                new MergeEqualCountIds($mergeIdsReceiver),
                new MergeValidateMergeIdsSender($this->userId),
                new MergeEnsureNotEmpty('merge_ids_sender'),
            ],
            'merge_ids_receiver' => [
                'required',
                'string',
                new MergeNoDuplicateIds(),
                new MergeValidateMergeIdReceiver($this->userId, $userMergeRequestId),
                new MergeEnsureNotEmpty('merge_ids_receiver'),

            ],

        ];
    }
}
