<?php

namespace App\GraphQL\Validators\Share;

use App\GraphQL\Enums\Status;
use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Rules\Share\CheckPersonOfEachUser;
use Exception;

class CheckPersonOfEachUserValidator extends GraphQLValidator
{
    use AuthUserTrait;
    use FindOwnerTrait;

    /**
     * Define the validation rules for the Create and Update Favorite inputs.
     */
    public function rules(): array
    {
        // Apply the custom rule 'CheckPersonOwner' to the 'person_id' field
        return [
            'person_id' => ['required',  new CheckPersonOfEachUser()],
        ];
    }
     /**
     * Optionally, you can define custom validation error messages here
     */
    public function messages(): array
    {
        return [
            'person_id.required' => 'The person_id field is required.',
           // 'person_id.integer' => 'The person_id must be an integer.',
        ];
    }
}
