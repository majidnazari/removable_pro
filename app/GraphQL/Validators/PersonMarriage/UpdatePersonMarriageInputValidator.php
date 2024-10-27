<?php

namespace App\GraphQL\Validators\PersonMarriage;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Rules\PersonMarriage\NotSelfMarriage;
use App\Rules\PersonMarriage\NotCloseRelativeMarriage;
use App\Rules\PersonMarriage\MarriageBeforeDivorceDate;
use App\Rules\PersonMarriage\OneActiveMarriage;
use App\Rules\PersonMarriage\UniqueMarriage;


class UpdatePersonMarriageInputValidator extends GraphQLValidator
{
    public function rules(): array
    {
        $marriageId = $this->arg('id'); // The ID of the current marriage being updated
        $manId = $this->arg('man_id');
        $womanId = $this->arg('woman_id');
        $marriageDate = $this->arg('marriage_date');
        $divorceDate = $this->arg('divorce_date');

        return [
            'man_id' => [
                'required',
                'exists:people,id',
                new OneActiveMarriage($manId, $marriageId), // Exclude the current marriage record
                new NotSelfMarriage($manId, $womanId),
                new NotCloseRelativeMarriage($manId, $womanId),
                new UniqueMarriage($manId, $womanId, $marriageId),
            ],
            'woman_id' => [
                'required',
                'exists:people,id',
                new OneActiveMarriage($womanId, $marriageId), // Exclude the current marriage record
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
