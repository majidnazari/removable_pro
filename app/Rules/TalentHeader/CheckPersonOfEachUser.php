<?php

namespace App\Rules\TalentHeader;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Traits\GetAllowedAllUsersInClan;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\MergeStatus;

use Log;


class CheckPersonOfEachUser implements Rule
{
    use AuthUserTrait;
    use FindOwnerTrait;
    use GetAllowedAllUsersInClan;
    protected $personId;
    protected $errorMessage = '';

    public function __construct()
    {
        //$this->personId = $personId;
    }

    public function passes($attribute, $value)
    {
        $this->personId = $value ?: $this->findOwner()->id;

        $person = Person::where('id', $this->personId)->first();

        if (!$person) {
            $this->errorMessage = "The selected person does not exist or is not accessible.";
            return false;
        }

        return true;

    }


    public function message()
    {
        return $this->errorMessage ?: "this person is not your own!";

    }
}
