<?php
namespace App\Rules\UserMergeRequest;


use App\GraphQL\Enums\MergeStatus;
use App\Models\Person;
use App\Models\User;
use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Log;

class UserSenderReceiverStatusCompleteOnce implements Rule
{
    protected $userSenderId;

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
        // Step 1: Get the sender's mobile using node_sender_id
        $senderPerson = Person::where('id', $value)->first();

        Log::info("The sender person is: ". json_encode($senderPerson));

        if (!$senderPerson || !$senderPerson->mobile) {
            return $this->failValidation('StatusComplete:Sender node does not exist.');
        }
        $senderMobile = $senderPerson->country_code . $senderPerson->mobile;
        // Step 3: Get receiver's user ID
        $receiverUser = User::where('mobile',  $senderMobile )->first();

        Log::info("The receiver person is: ". json_encode($receiverUser));

        if (!$receiverUser) {
            return $this->failValidation('StatusComplete:Receiver user not found.');
        }

        $receiverPerson = Person::where('creator_id', $receiverUser->id)
                    ->where('is_owner', true)
                    ->first();

        if (!$receiverPerson) {
            return $this->failValidation('StatusComplete:Receiver person not found.');
        }
       

        // Step 4: Check if a request with the same sender, receiver, and status = "complete" already exists
        $existingRequest = UserMergeRequest::where('user_sender_id',  $this->userSenderId) 
            ->where('user_receiver_id', $receiverUser->id) 
            ->where('status', MergeStatus::Complete)            
            ->first();

        Log::info("The UserMergeRequest StatusComplete is: ". json_encode($existingRequest));


        // If an existing request is found, prevent duplicates
        return $existingRequest ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
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
        return $this->message ?? 'A request with the same sender, receiver, and status "Complete" already exists.';
    }
}
