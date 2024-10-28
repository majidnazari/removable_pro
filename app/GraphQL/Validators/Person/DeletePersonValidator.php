<?php

namespace App\GraphQL\Validators\Person;

use App\Models\Person;
use App\Models\PersonChild;
use App\Models\PersonMarriage;
use Nuwave\Lighthouse\Validation\Validator;
use GraphQL\Error\Error;
use App\Rules\Person\ActiveChild;
use App\Rules\Person\ActiveMarriage;
use Log;

class DeletePersonValidator extends Validator
{
    public function rules(): array
    {
        $personId = $this->arg('id'); // Retrieve the person ID for validation

        return [
            'id' => [
                'required',
                'exists:people,id',
                new ActiveChild($personId),
                new ActiveMarriage($personId),
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
