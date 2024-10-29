<?php

namespace App\GraphQL\Validators\PersonChild;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Rules\PersonChild\ValidMarriage;
use App\Rules\MarriageBeforeChildBirth;
use App\Rules\PersonChild\UniquePersonChild;
use App\Rules\PersonChild\NotSelfChild;
use App\Rules\PersonChild\ChildAfterMarriageDate;
use App\Rules\PersonChild\ParentsAliveAtChildBirth;
use App\Rules\PersonChild\RealisticParentChildAgeGap;


class UpdatePersonChildInputValidator extends GraphQLValidator
{
    /**
     * Define the validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        $personChildId = $this->arg('id'); // Assuming `id` is provided in the GraphQL input for identifying the record
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
                new NotSelfChild($childId), // Prevents a person from being their own child
                new ChildAfterMarriageDate($personMarriageId),  // Ensures child's birth date is after marriage date
                new ParentsAliveAtChildBirth($personMarriageId), // Ensures parents were alive at birth
                new RealisticParentChildAgeGap($personMarriageId), // Ensures realistic age gap between parent and child
                new UniquePersonChild($personMarriageId, $childId), // Prevents duplicates, excluding the current record
            ],
        ];
    }
}
