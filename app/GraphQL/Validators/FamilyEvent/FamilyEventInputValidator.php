<?php

namespace App\GraphQL\Validators\FamilyEvent;

use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\Person;
use App\GraphQL\Enums\ConfirmMemoryStatus;
use Illuminate\Support\Facades\Auth;


use Exception;

class FamilyEventInputValidator extends Validator
{
    public function rules(): array
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        $personId = $this->arg('person_id');

        // Fetch the person by ID for conditional validation
        $person = Person::where('id', $personId)->where('creator_id', $user->id)->first();
        if (!$person) {
            throw new Exception("The specified person does not exist.");
        }

        return [
            'person_id' => [
                'required',
                'integer',
                'exists:persons,id', // Ensure person_id exists in the persons table
            ],
            'status' => [
                'nullable',
                Rule::in(['Active', 'Inactive', 'Suspend']), // Replace with your actual status options
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
