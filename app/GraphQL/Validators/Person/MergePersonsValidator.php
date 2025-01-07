<?php
namespace App\GraphQL\Validators\Person;

use App\Models\Person;
use App\GraphQL\Enums\Status;
use Nuwave\Lighthouse\Validation\Validator;
use GraphQL\Error\Error;

class MergePersonsValidator extends Validator
{
    /**
     * Validate the merge conditions.
     *
     * @return array
     */
    public function rules(): array
    {
        $primaryPersonId = $this->arg('primaryPersonId');
        $secondaryPersonId = $this->arg('secondaryPersonId');

        // Fetch the primary and secondary person by their IDs
        $primaryPerson = Person::where('id', $primaryPersonId)
            ->where('status', Status::Active)
            ->first();

        $secondaryPerson = Person::where('id', $secondaryPersonId)
            ->where('status', Status::Active)
            ->first();

        // Check if both persons exist
        if (!$primaryPerson || !$secondaryPerson) {
            throw new Error("One or both persons do not exist.");
        }

        // Check if both persons have the same gender
        if ($primaryPerson->gender !== $secondaryPerson->gender) {
            throw new Error("Persons cannot be merged because they have different genders.");
        }

        // Check if both persons are owners
        if (($primaryPerson->is_owner == 1) && ($secondaryPerson->is_owner == 1)) {
            throw new Error("Two people you have selected are both owners!");
        }

        // Check if both persons are the same person
        if ($primaryPerson->id == $secondaryPerson->id) {
            throw new Error("Persons cannot be merged because they are the same.");
        }

        // Return an empty array, as the rules are being handled in the constructor
        return [];
    }

    /**
     * Custom messages for errors
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'primaryPersonId.required' => 'The primary person ID is required.',
            'secondaryPersonId.required' => 'The secondary person ID is required.',
        ];
    }
}
