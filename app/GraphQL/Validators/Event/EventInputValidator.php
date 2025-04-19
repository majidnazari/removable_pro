<?php

namespace App\GraphQL\Validators\Event;

use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\Person;
use App\GraphQL\Enums\ConfirmMemoryStatus;

use Exception;

class EventInputValidator extends Validator
{
    public function rules(): array
    {
        $title = $this->arg('title');

        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not exceed 255 characters.',
            'title.min' => 'The title min is 3 characters.',

        ];
    }
}
