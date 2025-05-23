<?php

namespace App\GraphQL\Validators\Person;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use App\Models\PersonMarriage;
use App\Models\Person;
use Carbon\Carbon;
use App\Exceptions\CustomValidationException;


class CreateChildInputValidator extends GraphQLValidator
{
    public function rules(): array
    {
        $manId = $this->arg('man_id');
        $womanId = $this->arg('woman_id');
        $childData = $this->arg('child');

        // Fetch father and mother from database
        $father = Person::find($manId);
        $mother = Person::find($womanId);
        if (!$father || !$mother) {
            return ['parent_id' => ['Father or Mother not found']];
        }

        // Fetch marriage details
        $marriage = PersonMarriage::where('man_id', $manId)
            ->where('woman_id', $womanId)
            ->first();
        $marriageDate = $marriage->marriage_date ?? null;
        $divorceDate = $marriage->divorce_date ?? null;

        // Extract child birth date
        $childBirthDate = $childData['birth_date'];

        return [
            // Validate father and mother exist
            'man_id' => ['required', 'exists:people,id'],
            'woman_id' => ['required', 'exists:people,id'],

            // Validate child birth date
            'child.birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($childBirthDate, $father, $mother, $marriageDate, $divorceDate) {

                    // Ensure child birth date is at most 10 months after parent's birth date
                    $maxFatherDeathLimit = Carbon::parse($father['death_date'])->copy()->addMonths(10);
                    $maxMotherDeathLimit = Carbon::parse($mother['death_date'])->copy()->addMonths(10);

                    if (Carbon::parse($childBirthDate)->gt($maxFatherDeathLimit)) {
                        $fail("Child birth date must not be more than 10 months after father's death date.");
                    }

                    if (Carbon::parse($childBirthDate)->gt($maxMotherDeathLimit)) {
                        $fail("Child birth date must not be more than 10 months after mother's death date.");
                    }


                    // Ensure child birth date is after parents' minimum age
                    if ($value < date('Y-m-d', strtotime($father->birth_date . ' +12 years'))) {
                        $fail('Child birth date must be at least 12 years after father\'s birth date.');
                    }
                    if ($value < date('Y-m-d', strtotime($mother->birth_date . ' +9 years'))) {
                        $fail('Child birth date must be at least 9 years after mother\'s birth date.');
                    }

                    // Ensure child is born after marriage date (with 6 months gap)
                    if ($marriageDate && $value < date('Y-m-d', strtotime($marriageDate . ' +6 months'))) {
                        $fail('Child birth date must be at least 6 months after parents\' marriage date.');
                    }

                    //$minChildBirthDate = Carbon::parse($divorceDate)->copy()->addMonths(10);
                    $maxChildBirthDate = Carbon::parse($divorceDate)->copy()->addMonths(10);

                    if (Carbon::parse($value)->gt($maxChildBirthDate)) {
                        $fail('child.birth_date', 'Child birth date must be within 10 months after parents\' divorce date.');
                    }
                }
            ],
            'child.death_date' => ["nullable", "date", "after_or_equal:child.birth_date"],
        ];
    }
}
