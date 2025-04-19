<?php

namespace App\GraphQL\Validators\Address;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Rules\Share\AllUsersCanAccessPerson;
use Exception;

class CreateAddressValidator extends Validator
{
    protected $userId;

    public function rules(): array
    {
        $personId = $this->arg('person_id');

        return [
            'person_id' => [
                'required',
                'exists:people,id', // Ensure the person exists in the database

                new AllUsersCanAccessPerson($personId),
            ],
            'country_id' => [
                'nullable',
                'exists:countries,id', // Ensure the country exists
            ],
            'province_id' => [
                'nullable',
                'exists:provinces,id', // Ensure the province exists
            ],
            'city_id' => [
                'nullable',
                'exists:cities,id', // Ensure the city exists
            ],
            'location_title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'street_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'builder_no' => [
                'nullable',
                'string',
                'max:255',
            ],
            'floor_no' => [
                'nullable',
                'string',
                'max:255',
            ],
            'unit_no' => [
                'nullable',
                'string',
                'max:255',
            ],
            'lat' => [
                'nullable',
                'numeric',
            ],
            'lon' => [
                'nullable',
                'numeric',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'person_id.required' => 'The person ID is required.',
            'person_id.exists' => 'The specified person does not exist.',
            'country_id.exists' => 'The specified country does not exist.',
            'province_id.exists' => 'The specified province does not exist.',
            'city_id.required' => 'The city ID is required.',
            'city_id.exists' => 'The specified city does not exist.',
            'location_title.string' => 'The location title must be a string.',
            'location_title.max' => 'The location title may not be greater than 255 characters.',
            'street_name.string' => 'The street name must be a string.',
            'street_name.max' => 'The street name may not be greater than 255 characters.',
            'builder_no.string' => 'The builder number must be a string.',
            'builder_no.max' => 'The builder number may not be greater than 255 characters.',
            'floor_no.string' => 'The floor number must be a string.',
            'floor_no.max' => 'The floor number may not be greater than 255 characters.',
            'unit_no.string' => 'The unit number must be a string.',
            'unit_no.max' => 'The unit number may not be greater than 255 characters.',
            'lat.numeric' => 'The latitude must be a number.',
            'lon.numeric' => 'The longitude must be a number.',
            'status.in' => 'The status must be either "active" or "inactive".',
        ];
    }
}
