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
       
//       Log::info("the person id is:" .$this->personId);
       // $allowedCreatorIds = $this->getAllowedUserIds($this->getUserId());


//       Log::info("the all user alowed are :" . json_encode($allowedCreatorIds));
        $person = Person::where('id', $this->personId)->first();
        //->whereIn('creator_id', $allowedCreatorIds)->first();

        if (!$person) {
            $this->errorMessage = "The selected person does not exist or is not accessible.";
            return false;
        }


        // if (isset($person->is_owner) && $person->is_owner == 1 and $person->creator_id !== $this->getUserId()) {
        //     $this->errorMessage = "this person is owner and you cannot set talent to him/her !";
        //     return false;
        // }
//       Log::info(message: "the allowedCreatorIds is :" . json_encode($allowedCreatorIds));
       // return $person;
       return true;

    }


    public function message()
    {
        return $this->errorMessage ?: "this person is not your own!";

    }
}
