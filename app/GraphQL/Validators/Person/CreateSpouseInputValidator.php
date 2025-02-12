<?php

namespace App\GraphQL\Validators\Person;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Models\Person;
use App\Rules\Person\ValidBirthDate;
use App\Rules\Person\ValidMarriageDate;
use App\Rules\Person\ValidDivorceDate;
use Log;

class CreateSpouseInputValidator extends GraphQLValidator
{
    public function rules(): array
    {
        $personId = $this->arg('person_id');
        $spouseData = $this->arg('spouse');
        $marriageDate = $this->arg('marriage_date');
        $divorceDate = $this->arg('divorce_date');

        Log::info("the spouse is :" . json_encode($spouseData));
        // Fetch existing person from DB
        $person = Person::find($personId);

        if (!$person) {
            return ['person_id' => ['Person not found']];
        }

        // Extract spouse data (not saved in DB yet)
        $spouseBirthDate = $spouseData['birth_date'] ?? null;

        $manBirthdate = $person->gender == 1 ? $person->birth_date : $spouseBirthDate;
        $womanBirthdate = $person->gender == 1 ? $spouseBirthDate : $person->birth_date;
        //$spouseId = $spouseData['id'] ?? null; // Spouse is not saved, so we check input directly

        return [
            // Ensure person is not marrying themselves
            'person_id' => [
                'required',
                'exists:people,id',
                // new NotSelfMarriage($personId, $spouseId), // Custom rule to check self-marriage
            ],
            'spouse.first_name' => ['required', 'string'],
            'spouse.last_name' => ['required', 'string'],
            'spouse.birth_date' => ['required', 'date'],
            'spouse.death_date' => ['nullable', 'date', "after:spouse.birth_date"],

            // Marriage date must be after both birthdates
            'marriage_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
                new ValidMarriageDate($manBirthdate, $womanBirthdate),
                'required_with:divorce_date',  // marriage_date required if divorce_date exists
            ],

            // Divorce date validation
            'divorce_date' => [
                'nullable',
                'date',
                'after:marriage_date',
                'after:father.birth_date',
                'after:mother.birth_date',
                new ValidDivorceDate($marriageDate, $manBirthdate, $womanBirthdate),
            ],

            // Ensure unique marriage
            // new UniqueMarriage($personId, null), // Check if person already has an active marriage
        ];
    }
}
