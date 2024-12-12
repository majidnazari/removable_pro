<?php

namespace App\GraphQL\Validators\PersonChild;

use Illuminate\Validation\Validator;
use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Models\Person;
use App\Models\PersonMarriage;
use Carbon\Carbon;
use App\Rules\PersonChild\ValidMarriage;
use App\Rules\MarriageBeforeChildBirth;
use App\Rules\PersonChild\UniquePersonChild;
use App\Rules\PersonChild\NotSelfChild;
use App\Rules\PersonChild\ChildAfterMarriageDate;
use App\Rules\PersonChild\ParentsAliveAtChildBirth;
use App\Rules\PersonChild\RealisticParentChildAgeGap;
use App\Rules\Share\MatchCreator;
use App\Rules\Share\DateNotInFuture;


class CreatePersonChildInputValidator extends GraphQLValidator
{
    /**
     * Define the validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        $personMarriageId = $this->arg('person_marriage_id');
        $childId = $this->arg('child_id');
        return [
            'person_marriage_id' => [
                'required',
                new ValidMarriage,  // Ensures the marriage exists
            ],
            'child_id' => [
                'required',
                'exists:people,id',
                new NotSelfChild($this->arg('person_marriage_id')),  // Prevents a person from being their own child
                new ChildAfterMarriageDate($personMarriageId),  // Ensures child's birth date is after marriage date
                new ParentsAliveAtChildBirth($personMarriageId), // Ensures parents were alive at birth
                new RealisticParentChildAgeGap($personMarriageId), // Ensures realistic age gap between parent and child
                new UniquePersonChild($this->arg('person_marriage_id'), $this->arg('child_id')), // Prevents duplicates
                new MatchCreator(PersonMarriage::class,[$personMarriageId]), // Apply the custom rule to check all IDs
                new MatchCreator(Person::class,[$childId]), // Apply the custom rule to check all IDs
                new DateNotInFuture(Person::class, 'birth_date',$childId),

            ],
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'person_marriage_id.required' => 'The marriage ID is required.',
            'person_marriage_id.exists' => 'The specified marriage does not exist.',
            'child_id.required' => 'The child ID is required.',
            'child_id.exists' => 'The specified child does not exist.',
        ];
    }
}
