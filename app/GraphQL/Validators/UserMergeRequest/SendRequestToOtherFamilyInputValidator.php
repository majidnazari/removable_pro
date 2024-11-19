<?php

namespace App\GraphQL\Validators\UserMergeRequest;

use Nuwave\Lighthouse\Validation\Validator;

use App\Models\Person;
use App\Models\User;
use App\Models\UserMergeRequest;
use App\Rules\UserMergeRequest\PersonHasValidMobile;
use App\Rules\UserMergeRequest\PersonIsAccessibleBySender;
use App\Rules\UserMergeRequest\NoActiveRequestExists;
use App\GraphQL\Enums\Status;

use GraphQL\Error\Error;

class SendRequestToOtherFamilyInputValidator extends Validator
{
    protected $user_sender_id;

    public function __construct()
    {
        $this->user_sender_id = auth()->guard('api')->user()->id;
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
            ],
        ];
    }

    /**
     * Custom validation logic after field-level validation.
     */
    public function after($validator)
    {
        // Fetch the sender person
        $node_sender_id = $this->data['node_sender_id'];
        $person = Person::find($node_sender_id);

        // Ensure the receiver exists
        $user_reciver = User::where('mobile', $person->country_code . $person->mobile)
            ->where('status', Status::Active)
            ->first();

        if (!$user_reciver) {
            $validator->errors()->add('user_reciver_id', 'The node you have selected is not found.');
            return;
        }

        // Ensure sender and receiver are different
        if ($this->user_sender_id === $user_reciver->id) {
            $validator->errors()->add('user_reciver_id', 'The sender and receiver cannot be the same.');
            return;
        }

        // Ensure the receiver's owner exists
        $person_reciver_owner = Person::where('creator_id', $user_reciver->id)
            ->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        if (!$person_reciver_owner) {
            $validator->errors()->add('node_reciver_id', 'The receiver\'s owner is not found.');
        }
    }
}
