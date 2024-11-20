<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;

use App\Rules\UserMergeRequest\MergeIdsAreDifferent;
use App\Rules\UserMergeRequest\MergeNoDuplicateIds;
use App\Rules\UserMergeRequest\MergeValidateNodeIdsInMergeIds;
use App\Rules\UserMergeRequest\MergeValidateMergeIdsWithinActiveRelations;
use App\Rules\UserMergeRequest\MergeEqualCountIds;
use Log;

class SendMergeRequestToOtherFamilyInputValidator extends Validator
{
    protected $userId;

    public function __construct()
    {
        $this->userId = auth()->guard('api')->user()->id;
    }

    /**
     * Return the validation rules for the input.
     */
    public function rules(): array
    {
        $mergeIdsSender = $this->arg('merge_ids_sender');
        $userMergeRequestId = $this->arg('id');
        $mergeIdsReceiver = $this->arg('merge_ids_reciver');
        return [
            'id' => [
                'required',
                'exists:user_merge_requests,id',
                new MergeValidateNodeIdsInMergeIds($userMergeRequestId, $mergeIdsSender, $mergeIdsReceiver), // Validate sender and receiver node IDs
            ],
            'merge_ids_sender' => [
                'required',
                'string',
               new MergeIdsAreDifferent($mergeIdsReceiver),
               new MergeNoDuplicateIds(), 
               new MergeEqualCountIds($mergeIdsReceiver),
               new MergeValidateMergeIdsWithinActiveRelations($this->userId),
            ],
            'merge_ids_reciver' => [
                'required',
                'string',
                new MergeNoDuplicateIds(), 
                new MergeValidateMergeIdsWithinActiveRelations($this->userId),
            ],
           
        ];
    }
}
