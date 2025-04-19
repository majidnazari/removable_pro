<?php

namespace App\GraphQL\Validators\PersonMarriage;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Rules\PersonMarriage\NotSelfMarriage;
use App\Rules\PersonMarriage\NotCloseRelativeMarriage;
use App\Rules\PersonMarriage\MarriageBeforeDivorceDate;
use App\Rules\PersonMarriage\OneActiveMarriage;
use App\Rules\PersonMarriage\UniqueMarriage;
use App\Rules\PersonMarriage\OppositeGenderMarriage;
use App\Rules\Share\MatchCreator;
use App\Models\Person;

class CreatePersonMarriageInputValidator extends GraphQLValidator
{
    public function rules(): array
    {
        $manId = $this->arg('man_id');
        $womanId = $this->arg('woman_id');
        $marriageDate = $this->arg('marriage_date');
        $divorceDate = $this->arg('divorce_date');

        return [
            'man_id' => [
                'required',
                'exists:people,id',
                //new OneActiveMarriage($manId),
                new NotSelfMarriage($manId, $womanId),
                new NotCloseRelativeMarriage($manId, $womanId),
                new UniqueMarriage($manId, $womanId),
                // new OppositeGenderMarriage($manId, $womanId), // Ensure opposite gender marriage
                new MatchCreator(Person::class, [$manId, $womanId]), // Apply the custom rule to check all IDs
            ],
            'woman_id' => [
                'required',
                'exists:people,id',
                //new OneActiveMarriage($womanId),
            ],
            'marriage_date' => [
                'nullable',
                'date',
                new MarriageBeforeDivorceDate($marriageDate, $divorceDate),
            ],
            'divorce_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}
