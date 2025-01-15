<?php

namespace App\GraphQL\Validators\Favorite;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\Favorite\MaxRecordsForPerson;
use App\Rules\Share\CheckPersonOwner;

class UpdateFavoriteInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        $recordId = $this->arg('id'); // Assuming 'id' is passed as an argument for the record being updated

        return [
            'person_id' => [
                'nullable',
                'exists:people,id',
                new MaxRecordsForPerson($this->arg('person_id'), $recordId),
                new CheckPersonOwner(),
            ],
            
        ];
    }
    public function messages(): array
    {
        return [
            'person_id.required' => 'The person_id field is required.',
            'person_id.exists' => 'The specified person does not exist.',
        ];
    }
}
