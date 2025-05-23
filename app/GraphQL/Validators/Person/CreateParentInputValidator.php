<?php

namespace App\GraphQL\Validators\Person;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\Person\ValidBirthDate;
use App\Rules\Person\ValidMarriageDate;
use App\Rules\Person\ValidDivorceDate;
use App\Rules\Person\ValidDeathDate;
use Exception;
use Log;

class CreateParentInputValidator extends Validator
{
    public function rules(): array
    {
        $fatherBirthDate = $this->arg('father')['birth_date'] ?? null;
        $motherBirthDate = $this->arg('mother')['birth_date'] ?? null;
        $marriageDate = $this->arg('marriage_date') ?? null;
        $divorceDate = $this->arg('divorce_date') ?? null;
        //       Log::info("Passing to ValidDeathDate: father=$fatherBirthDate, mother=$motherBirthDate, marriage=$marriageDate, divorce=$divorceDate");

        //       Log::info("th all are :" . json_encode($this->arg("father.birth_date")));
        return [
            'person_id' => ['required', 'exists:people,id'],

            'father.first_name' => ['required', 'string', 'max:255'],
            'father.last_name' => ['required', 'string', 'max:255'],
            'father.birth_date' => ['required', 'date', 'before_or_equal:today', new ValidBirthDate],
            'father.death_date' => ['nullable', 'date', 'after_or_equal:father.birth_date', new ValidDeathDate($fatherBirthDate, $motherBirthDate, $marriageDate, $divorceDate)],
            //'father.gender' => ['required', 'integer', 'in:1'], // 1 = Male

            'mother.first_name' => ['required', 'string', 'max:255'],
            'mother.last_name' => ['required', 'string', 'max:255'],
            'mother.birth_date' => ['required', 'date', 'before_or_equal:today', new ValidBirthDate],
            'mother.death_date' => ['nullable', 'date', 'after_or_equal:mother.birth_date', new ValidDeathDate($fatherBirthDate, $motherBirthDate, $marriageDate, $divorceDate)],

            'marriage_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
                new ValidMarriageDate($this->arg("father.birth_date"), $this->arg("mother.birth_date")),
                'required_with:divorce_date',  // marriage_date required if divorce_date exists
            ],

            // divorce_date must be after marriage_date and after both parents' birth dates
            'divorce_date' => [
                'nullable',
                'date',
                'after_or_equal:marriage_date',
                'after_or_equal:father.birth_date',
                'after_or_equal:mother.birth_date',
                new ValidDivorceDate($this->arg("marriage_date"), $this->arg("father.birth_date"), $this->arg("mother.birth_date")),
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'person_id.required' => 'The child person ID is required.',
            'person_id.exists' => 'The specified child person does not exist.',

            'father.first_name.required' => 'Father\'s first name is required.',
            'father.last_name.required' => 'Father\'s last name is required.',
            'father.birth_date.required' => 'Father\'s birth date is required.',
            'father.gender.in' => 'Father must be male (gender = 1).',

            'mother.first_name.required' => 'Mother\'s first name is required.',
            'mother.last_name.required' => 'Mother\'s last name is required.',
            'mother.birth_date.required' => 'Mother\'s birth date is required.',
            'mother.gender.in' => 'Mother must be female (gender = 0).',

            'marriage_date.required_with' => 'Marriage date is required if a divorce date is provided.',  // Custom message for marriage_date
            'marriage_date.before_or_equal' => 'Marriage date cannot be in the future.',
            'divorce_date.after_or_equal' => 'Divorce date must be after the marriage date and both parents\' birth dates.',
        ];
    }
}
