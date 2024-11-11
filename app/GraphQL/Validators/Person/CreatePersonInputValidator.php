<?php

namespace App\GraphQL\Validators\Person;

use App\Rules\UniquePerson;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

class CreatePersonInputValidator extends Validator
{
    public function rules(): array
    {
        //$id = $this->arg('id');  // Get the id argument for update
        $firstName = $this->arg('first_name');
        $lastName = $this->arg('last_name');
        $birthDate = $this->arg('birth_date');

        return [
            
            'first_name' => [
                'sometimes',
                'string',
                'max:255',
               // new UniquePerson($firstName, $lastName, $birthDate, $id),
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
