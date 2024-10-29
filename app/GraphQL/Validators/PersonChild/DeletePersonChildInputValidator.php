<?php

namespace App\GraphQL\Validators\PersonChild;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\PersonChild\DeletablePersonChild;

class DeletePersonChildInputValidator extends Validator
{
    public function rules(): array
    {
        $Id = $this->arg('id'); // Get the person-child child_id for validation

        return [
            'id' => [
                'required',
                'exists:person_children,id', // Ensure the record exists
                new DeletablePersonChild($Id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The person-child relationship child_id is required for deletion.',
            'id.exists' => 'The specified person-child relationship does not exist.',
        ];
    }
}
