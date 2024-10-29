<?php

namespace App\GraphQL\Validators\PersonMarriage;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\PersonMarriage\ActiveChildrenInMarriage;

class DeletePersonMarriageInputValidator extends Validator
{
    public function rules(): array
    {
        $personMarriageId = $this->arg('id'); // Retrieve the marriage ID for validation

        return [
            'id' => [
                'required',
                'exists:person_marriages,id',
                new ActiveChildrenInMarriage($personMarriageId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The marriage ID is required for deletion.',
            'id.exists' => 'The specified marriage does not exist.',
        ];
    }
}
