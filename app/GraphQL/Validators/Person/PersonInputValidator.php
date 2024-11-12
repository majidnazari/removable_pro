<?php

namespace App\GraphQL\Validators\Person;


use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\Person\UniquePerson;

class PersonInputValidator extends Validator
{
    public function rules(): array
    {
        $personId = $this->arg('id'); // Retrieve the person ID for validation

        return [
            'id' => [
                'required',
                'exists:people,id',               
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The person ID is required for deletion.',
            'id.exists' => 'The person with the specified ID does not exist.',
        ];
    }
}
