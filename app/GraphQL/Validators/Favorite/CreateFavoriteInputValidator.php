<?php

namespace App\GraphQL\Validators\Favorite;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\Favorite\MaxRecordsForPerson;
use App\Rules\Share\CheckPersonOwner;

class CreateFavoriteInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'person_id' => [
                'required',
                'exists:people,id',
                new MaxRecordsForPerson($this->arg('person_id')),
                new CheckPersonOwner()
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
