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

use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

use GraphQL\Error\Error;

class SendRequestToOtherFamilyInputValidator extends Validator
{
    protected $user_sender_id;

    public function __construct()
    {
        
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->user_sender_id = $user->id;
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
                new PersonIsAccessibleBySender($this->user_sender_id),
                new NoActiveRequestExists($this->user_sender_id), 

                new NodeSenderExists(),
                new NodeSenderNotOwner(),
                new ReceiverExists(),
                new SenderCannotSendToItself($this->user_sender_id),
                new ReceiverHasOwner(),
                new SenderNodeAndReceiverNodeSameGender(),
                new UserSenderReceiverStatusCompleteOnce(),
            ],
        ];
    }

    /**
     * Custom validation logic after field-level validation.
     */
//     public function after($validator)
//     {
//         // Fetch the sender person
//         $node_sender_id = $this->data['node_sender_id'];
//         $person = Person::find($node_sender_id);

//         Log::info("the person foundis:". json_encode($person));

//         if (!$person) {
//             $validator->errors()->add('node_sender_id', 'The selected node sender does not exist.');
//             return;
//         }

//         // Check if the sender is an owner
//         if ($person->is_owner) {
//             $validator->errors()->add('node_sender_id', 'The owner cannot send requests to others.');
//             return;
//         }

//         // Ensure the receiver exists
//         $user_receiver = User::where('mobile', $person->country_code . $person->mobile)
//             ->where('status', Status::Active)
//             ->first();

//         if (!$user_receiver) {
//             $validator->errors()->add('user_receiver_id', 'The node you have selected is not found.');
//             return;
//         }

//         // Ensure sender and receiver are different
//         if ($this->user_sender_id === $user_receiver->id) {
//             $validator->errors()->add('user_receiver_id', 'The sender and receiver cannot be the same.');
//             return false;
//         }

//         // Ensure the receiver's owner exists
//         $person_receiver_owner = Person::where('creator_id', $user_receiver->id)
//             ->where('is_owner', true)
//             ->where('status', Status::Active)
//             ->first();

//         if (!$person_receiver_owner) {
//             $validator->errors()->add('node_receiver_id', 'The receiver\'s owner is not found.');
//         }

        
//     }
 }
