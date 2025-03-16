<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Traits\GetAllBloodPersonsInClanFromHeads;

class PersonInBloodline implements Rule
{
    use GetAllBloodPersonsInClanFromHeads;

    protected $personId;
    protected $userId;

    public function __construct($personId, $userId)
    {
        $this->personId = $personId;
        $this->userId = $userId;
    }

    public function passes($attribute, $value)
    {
        // Get all blood relatives of the user
        $bloodPersonIds = $this->getAllBloodPersonsInClanFromHeads($this->userId);

        // Check if the person belongs to the bloodline
        return in_array($this->personId, $bloodPersonIds);
    }

    public function message()
    {
        return "You can only create a spouse for yourself or a person within your bloodline.";
    }
}
