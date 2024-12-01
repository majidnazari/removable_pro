<?php
namespace App\Rules\UserMergeRequest;

use App\Models\User;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class SenderNodeAndReceiverNodeSameGender implements Rule
{
    protected $nodeSenderId;

    // Constructor is no longer needed to accept arguments, as it gets the value directly from the input.
    public function __construct()
    {
        // No need to store nodeSenderId here
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
        if (!$senderPerson || !$senderPerson->mobile) {
            return false; // Sender does not have a mobile
        }

        $senderMobile = $senderPerson->country_code . $senderPerson->mobile;//$senderPerson->mobile;

        // Step 2: Get the receiver using the sender's mobile number
        $receiver = User::where('mobile', $senderMobile)->first();
        if (!$receiver) {
            return false; // Receiver not found
        }

        // Step 3: Compare the genders of sender and receiver
        $senderGender = User::find($nodeSenderId)->gender;
        $receiverGender = $receiver->gender;

        return $senderGender === $receiverGender; // Ensure both genders match
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sender node and receiver node must have the same gender.';
    }
}
