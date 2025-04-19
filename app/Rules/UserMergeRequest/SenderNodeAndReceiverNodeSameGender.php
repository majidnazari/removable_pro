<?php

namespace App\Rules\UserMergeRequest;

use App\Models\User;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use Log;

class SenderNodeAndReceiverNodeSameGender implements Rule
{
    protected $nodeSenderId;
    protected $userSenderId;
    protected $message;

    // Constructor is no longer needed to accept arguments, as it gets the value directly from the input.
    public function __construct($user_sender_id)
    {
        // No need to store nodeSenderId here
        $this->userSenderId=$user_sender_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Retrieve node_sender_id directly from the $value (the input parameter)
        $nodeSenderId = $value;  // $value will be the node_sender_id provided in the request

        // Step 1: Fetch the sender's mobile from the 'person' table using node_sender_id
        $senderPerson = Person::where('id', $nodeSenderId)->first();
        if (!$senderPerson) {
            return $this->failValidation('SameGender:Sender node does not exist.');
        }

        if (!$senderPerson->mobile) {
            return $this->failValidation('SameGender:Sender does not have a mobile number.');
        }

        //Log::info("The sender person is: ". json_encode($senderPerson));

        $senderMobile = $senderPerson->country_code . $senderPerson->mobile;

        // Step 2: Get the receiver using the sender's mobile number
        $receiverUser = User::where('mobile', $senderMobile)->first();
        if (!$receiverUser) {
            return $this->failValidation('SameGender:Receiver user not found.');
        }

       // Log::info("The receiver person is: ". json_encode($receiverUser));

        $receiverPerson = Person::where('creator_id', $receiverUser->id)
            ->where('is_owner', true)
            ->first();

        if (!$receiverPerson) {
            return $this->failValidation('SameGender:Receiver person not found.');
        }

        // Step 3: Compare the genders of sender and receiver
        if ($senderPerson->gender !== $receiverPerson->gender) {
            return $this->failValidation('SameGender:Sender node and receiver node must have the same gender.');
        }

        return true;
    }

    /**
     * Helper method to return custom validation error message.
     *
     * @param  string  $message
     * @return bool
     */
    private function failValidation($message)
    {
        $this->message = $message;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'Sender node and receiver node must have the same gender.';
    }
}
