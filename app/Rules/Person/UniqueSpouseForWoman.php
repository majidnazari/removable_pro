<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;
use Carbon\Carbon;
use Log;

class UniqueSpouseForWoman implements Rule
{
    protected $person;
    protected $spouseData;
    protected $marriageDate;
    protected $errorMessage;

    public function __construct($person, $spouseData, $marriageDate)
    {
        $this->person = $person;
        $this->spouseData = $spouseData;
        $this->marriageDate =Carbon::parse( $marriageDate);
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
                ->get();

            // Check if any of the husbands is still alive
            foreach ($hasActiveMarriage as $marriage) {
                $husband = Person::find($marriage->man_id);
                
                if (($husband && is_null($husband->death_date)) || (Carbon::parse($husband->death_date)->gt($this->marriageDate))) {
                    // If at least one husband is alive, deny remarriage
                    $this->errorMessage = "This woman already has an active marriage with a living husband.";
                    return false;
                }
            }
        }

        // If the person is deceased, they cannot remarry
        if (!empty($this->person->death_date) && ($this->marriageDate->gt(Carbon::parse($this->person->death_date)))) {
            $this->errorMessage = "A dead person cannot marry.";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage ?? "Invalid spouse assignment.";
    }
}
