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

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    public function passes($attribute, $value)
    {
        // Log the entire request payload for debugging
        $requestData = request()->all();
        // Log::info("Request Data: " . json_encode($requestData));

        // Attempt to extract `person_id` directly from the `variables` or fallback to parsing the query
        $personId = null;

        // Check if `variables` exist and contain `input.person_id`
        if (!empty($requestData['variables']['input']['person_id'])) {
            $personId = $requestData['variables']['input']['person_id'];
        } else {
            // Parse `query` to extract `person_id` if it's inline
            if (!empty($requestData['query'])) {
                preg_match('/person_id\s*:\s*"(\d+)"/', $requestData['query'], $matches);
                $personId = $matches[1] ?? null;
            }
        }
        $this->personId = $personId;
        //Log::info("Extracted person_id: " . $personId);

        // Log::info("The person id is: " . $personId);
        // Log::info("the person id is:" .$this->personId);
        // Check if the person has any active children
        $allowedCreatorIds = $this->getAllowedUserIds($this->getUserId());
        $person = Person::where('id', $this->personId)->whereIn('creator_id', $allowedCreatorIds)->first();

        if (isset($person->is_owner) && $person->is_owner == 1 and $person->creator_id !== $this->getUserId()) {
            $this->errorMessage = "this person is owner and you cannot set talent to him/her !";
            return false;
        }

        // log::info("the person is :" . json_encode($person));
        // if (($person) && ($person->is_owner == true) && ($person->creator_id != $this->getUserId())) {
        //     return false;
        // }
        //Log::info(message: "the allowedCreatorIds is :" . json_encode($allowedCreatorIds));
        return $person;

    }


    public function message()
    {
        return $this->errorMessage ?: "this person is not your own!";

    }
}
