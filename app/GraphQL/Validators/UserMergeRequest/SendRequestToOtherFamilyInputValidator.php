<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;

use App\Models\Person;
use App\Models\User;
use App\Models\UserMergeRequest;
use App\Rules\UserMergeRequest\PersonHasValidMobile;
use App\Rules\UserMergeRequest\PersonIsAccessibleBySender;
use App\Rules\UserMergeRequest\NoActiveRequestExists;

use App\Rules\UserMergeRequest\NodeSenderExists;
use App\Rules\UserMergeRequest\NodeSenderNotOwner;
use App\Rules\UserMergeRequest\ReceiverExists;
use App\Rules\UserMergeRequest\SenderCannotSendToItself;
use App\Rules\UserMergeRequest\ReceiverHasOwner;
use App\Rules\UserMergeRequest\SenderNodeAndReceiverNodeSameGender;
use App\Rules\UserMergeRequest\UserSenderReceiverStatusCompleteOnce;
use App\Traits\GetAllBloodPersonsWithSpousesInClanFromHeads;
use App\Exceptions\CustomValidationException;

use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

use GraphQL\Error\Error;

class SendRequestToOtherFamilyInputValidator extends Validator
{
    use GetAllBloodPersonsWithSpousesInClanFromHeads;
    protected $user_sender_id;
    protected $node_id;

    public function __construct()
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new CustomValidationException("Authentication required. No user is currently logged in.", "احراز هویت لازم است. هیچ کاربری در حال حاضر وارد نشده است.", 403);

            //throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->user_sender_id = $user->id;
        // $this->node_id = $this->arg('node_sender_id');
        //$this->args = request()->input();

        //Log::info("the args are " . json_encode($this->args));
    }

    /**
     * Return the validation rules for the input.
     */
    public function rules(): array
    {
        return [
            'node_sender_id' => [
                'required',
                'exists:people,id',
                new PersonHasValidMobile(),
                //new PersonIsAccessibleBySender($this->user_sender_id),
                new NoActiveRequestExists($this->user_sender_id),

                new NodeSenderExists(),
                new NodeSenderNotOwner(),
                //new ReceiverExists(),
                new SenderCannotSendToItself($this->user_sender_id),
                new ReceiverHasOwner(),
                new SenderNodeAndReceiverNodeSameGender($this->user_sender_id),
                new UserSenderReceiverStatusCompleteOnce($this->user_sender_id),
                function ($attribute, $value, $fail) {
                    Log::info("the person must to check the in clan is :".$value);
                    $allowedPersons = $this->getAllBloodPersonsWithSpousesInClanFromHeads( $this->user_sender_id);

                    Log::info("Allowed persons for sender {$this->user_sender_id}: " . json_encode($allowedPersons));

                    if (!in_array($value, $allowedPersons)) {
                        return $fail("The selected sender node is not part of the sender's bloodline in the clan.");
                    }
                }
            ],
        ];
    }


}
