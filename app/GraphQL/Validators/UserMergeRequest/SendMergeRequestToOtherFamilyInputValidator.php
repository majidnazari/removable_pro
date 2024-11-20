<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;



use App\Rules\UserMergeRequest\MergeIdsAreDifferent;
use App\Rules\UserMergeRequest\MergeIdsReceiverValid;
use App\Rules\UserMergeRequest\MergeIdsSenderOwnership;
//use App\Rules\UserMergeRequest\MergeIdsCountMatch;
use App\Rules\UserMergeRequest\NoDuplicateMergeRequest;
use Log;

class SendMergeRequestToOtherFamilyInputValidator extends Validator
{
    protected $userSenderId;

    public function __construct()
    {
        $this->userSenderId = auth()->guard('api')->user()->id;
    }

    /**
     * Return the validation rules for the input.
     */
    public function rules(): array
    {
        $merge_ids_sender = $this->arg('merge_ids_sender');
        $merge_ids_reciver = $this->arg('merge_ids_reciver');
        $mergeIdsReceiver = $this->arg('merge_ids_reciver');
        return [
            'id' => [
                'required',
                'exists:user_merge_requests,id',
                //new MergeActiveRequestExists(), // Checks if the request is active
            ],
            'merge_ids_sender' => [
                'required',
                'string',
               // new MergeIdsSenderOwnership($this->userSenderId, $merge_ids_sender ), // Validates sender ownership
               new MergeIdsAreDifferent($mergeIdsReceiver),
            ],
            'merge_ids_reciver' => [
                'required',
                'string',
               // new MergeIdsReceiverValid($this->userSenderId,$merge_ids_reciver), // Validates receiver validity
            ],
           
        ];
    }
}
