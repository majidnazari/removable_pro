<?php

namespace App\GraphQL\Validators\PersonChild;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\PersonChild\DeletablePersonChildByChildId;

class DeletePersonChildByChildIdInputValidator extends Validator
{
    public function rules(): array
    {
        $childId = $this->arg('child_id'); // Get the person-child child_id for validation

        return [
            'child_id' => [
                'required',
                'exists:person_children,child_id', // Ensure the record exists
                new DeletablePersonChildByChildId($childId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'The person-child relationship child_id is required for deletion.',
            'child_id.exists' => 'The specified person-child relationship does not exist.',
        ];
    }
}
