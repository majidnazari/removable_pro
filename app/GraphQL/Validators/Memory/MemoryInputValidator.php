<?php

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\Person;
use App\GraphQL\Enums\ConfirmMemoryStatus;

use Exception;

class MemoryInputValidator extends Validator
{
    public function rules(): array
    {
        $personId = $this->arg('person_id');

        // Fetch the person by ID for conditional validation
        $person = Person::where('id', $personId)->first();
        if (!$person) {
            throw new Exception("The specified person does not exist.");
        }

        return [
            'person_id' => [
                'required',
                'integer',
                'exists:persons,id', // Ensure person_id exists in the persons table
            ],
            'category_content_id' => [
                'required',
                'integer',
                'exists:categories,id', // Replace 'categories' with the actual table name
            ],
            'group_category_id' => [
                'nullable',
                'integer',
                'exists:group_categories,id', // Replace 'group_categories' with the actual table name
            ],
            // 'confirm_status' => [
            //     'nullable',
            //     Rule::in([
            //         ConfirmMemoryStatus::Reject->value,
            //         ConfirmMemoryStatus::Suspend->value,
            //         ConfirmMemoryStatus::Accept->value,
            //     ]),
            // ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'content' => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_shown_after_death' => [
                'nullable',
                'boolean',
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
            'category_content_id.required' => 'The category_content_id field is required.',
            'category_content_id.exists' => 'The specified category does not exist.',
            'group_category_id.exists' => 'The specified group category does not exist.',
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not exceed 255 characters.',
            'confirm_status.in' => 'The confirm_status must be one of the valid options.',
            'is_shown_after_death.boolean' => 'The is_shown_after_death field must be true or false.',
        ];
    }
}
