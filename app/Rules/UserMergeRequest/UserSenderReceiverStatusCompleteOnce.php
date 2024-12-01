<?php
namespace App\Rules\UserMergeRequest;


use App\GraphQL\Enums\MergeStatus;
use App\Models\Person;
use App\Models\User;
use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserSenderReceiverStatusCompleteOnce implements Rule
{
    protected $userSenderId;

    public function __construct()
    {
        // Get the logged-in user's ID
       
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
        // Step 1: Get the sender's mobile using node_sender_id
        $senderPerson = Person::where('id', $value)->first();
        if (!$senderPerson || !$senderPerson->mobile) {
            return false; // No mobile found for sender
        }
        $senderMobile = $senderPerson->country_code . $senderPerson->mobile;

        // Step 2: Find the receiver using sender's mobile
        $receiverPerson = Person::where('mobile', $senderMobile)->first();
        if (!$receiverPerson) {
            return false; // No receiver found using sender's mobile
        }

        // Step 3: Get receiver's user ID
        $receiverUser = User::where('mobile',  $senderMobile )->first();
        if (!$receiverUser) {
            return false; // No user found for receiver
        }

        // Step 4: Check if a request with the same sender, receiver, and status = "complete" already exists
        $existingRequest = UserMergeRequest::where('user_sender_id',  $senderPerson->id) 
            ->where('user_receiver_id', $receiverUser->id) 
            ->where('status', MergeStatus::Complete) 
           
            ->first();

        // If an existing request is found, prevent duplicates
        return $existingRequest ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A request with the same sender, receiver, and status "Complete" already exists.';
    }
}
