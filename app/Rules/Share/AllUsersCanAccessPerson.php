<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Traits\GetAllowedAllUsersInClan;
use App\Traits\GetAllUsersRelationInClanFromHeads;
use App\Traits\UpdateUserRelationTrait;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\MergeStatus;

use Log;


class AllUsersCanAccessPerson implements Rule
{
    use AuthUserTrait;
    use FindOwnerTrait;
    use GetAllowedAllUsersInClan;
    use GetAllUsersRelationInClanFromHeads;
    use UpdateUserRelationTrait;

    protected $personId;
    protected $errorMessage = '';

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    public function passes($attribute, $value)
    {

        $allowedCreatorIds = $this->getAllUsersInClanFromHeads($this->getUserId());

        $person = Person::where('id', $this->personId)->whereIn('creator_id', $allowedCreatorIds)->first();

        if (isset($person->is_owner) && $person->is_owner == 1 and $person->creator_id !== $this->getUserId()) {
            $this->errorMessage = "this person is owner and you cannot chnage it!";
            return false;
        }
        return true;

    }


    public function message()
    {
        return $this->errorMessage ?: "this person is not your own!";

    }
}
