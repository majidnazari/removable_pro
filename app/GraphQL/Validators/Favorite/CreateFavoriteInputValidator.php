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
            'title' => [
                "required",
                new MaxRecordsForPerson(null),
            ]

        ];
    }
    public function messages(): array
    {
        return [

            'person_id.exists' => 'The specified person does not exist.',
            'title.required' => 'The title is reuired!',
        ];
    }
}
