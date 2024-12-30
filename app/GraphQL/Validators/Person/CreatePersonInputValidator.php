<?php

namespace App\GraphQL\Validators\Person;


use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\Person\UniquePerson;
use App\Rules\Person\UniqueOwnerPerUser;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

class CreatePersonInputValidator extends Validator
{
    protected $userId;

    public function rules(): array
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;

        //Log::info("the user is:" . $this->userId);
        //$id = $this->arg('id');  // Get the id argument for update
        $firstName = $this->arg('first_name');
        $lastName = $this->arg('last_name');
        $birthDate = $this->arg('birth_date');
        $is_owner = $this->arg('is_owner');

        return [
            
            'first_name' => [
                'sometimes',
                'string',
                'max:255',
               new UniquePerson($firstName, $lastName, $birthDate),
               new UniqueOwnerPerUser($this->userId, $is_owner),
            ],
            'last_name' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'birth_date' => [
                'sometimes',
                'date',
                'before_or_equal:today',  // Ensures birth_date is not in the future
            ],
            'death_date' => [
                'nullable',
                'date',
                'before_or_equal:today', // Ensures death_date is not in the future
                'after_or_equal:birth_date', // Ensures death_date is not before birth_date
            ],
            'gender' => [
                'sometimes',
                'integer',
                'in:0,1',  // Only allows 0 (Man) or 1 (Woman)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The person ID is required for an update.',
            'id.exists' => 'The person with the specified ID does not exist.',
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'birth_date.required' => 'The birth date is required.',
            'birth_date.before_or_equal' => 'The birth date cannot be in the future.',
            'death_date.before_or_equal' => 'The death date cannot be in the future.',
            'death_date.after_or_equal' => 'The death date cannot be before the birth date.',
            'gender.in' => 'The gender must be either 0 (Man) or 1 (Woman).',
            'first_name.regex' => 'The first name may only contain letters, spaces, and hyphens.',
            'last_name.regex' => 'The last name may only contain letters, spaces, and hyphens.',
        ];
    }
}
