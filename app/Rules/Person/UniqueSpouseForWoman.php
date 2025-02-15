<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;

class UniqueSpouseForWoman implements Rule
{
    protected $person;
    protected $spouseData;
    protected $errorMessage;

    public function __construct($person, $spouseData)
    {
        $this->person = $person;
        $this->spouseData = $spouseData;
    }

    public function passes($attribute, $value)
    {
        // Check if spouse already exists in the table
        $existingSpouse = Person::where('first_name', $this->spouseData['first_name'])
            ->where('last_name', $this->spouseData['last_name'])
            ->where('birth_date', $this->spouseData['birth_date'])
            ->first();

        if ($existingSpouse) {
            $this->errorMessage = "A person with the same name and birth date already exists.";
            return false;
        }

        // If person is a woman, ensure she has no active marriage
        if ($this->person->gender == 0) {
            $hasActiveMarriage = PersonMarriage::where('woman_id', $this->person->id)
                ->whereNull('divorce_date')
                ->exists();

            if ($hasActiveMarriage) {
                $this->errorMessage = "This woman already has an active marriage.";
                return false;
            }
        }

        // If the person is deceased, they cannot remarry
        if (!empty($this->person->death_date)) {
            $this->errorMessage = "A deceased person cannot remarry.";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
