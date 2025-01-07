<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\MergeStatus;

use Log;


class CheckPersonOfEachUser implements Rule
{
    use AuthUserTrait;
    use FindOwnerTrait;
    protected $personId;

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
        $this->personId=$personId;
        //Log::info("Extracted person_id: " . $personId);

        // Log::info("The person id is: " . $personId);
        // Log::info("the person id is:" .$this->personId);
        // Check if the person has any active children
        $allowedCreatorIds = $this->getAllowedCreatorIds($this->getUserId());
        $person = Person::where('id', $this->personId)->whereIn('creator_id', $allowedCreatorIds)->exists();

        //Log::info(message: "the allowedCreatorIds is :" . json_encode($allowedCreatorIds));
        return $person;

    }
    protected function getAllowedCreatorIds(int $userId): array
    {
        // Start with the logged-in user ID
        $allowedCreatorIds = [$userId];

        // Get all user_receiver_id values where the logged-in user is the sender and status is Complete (4)
        $connectedUserIds = DB::table('user_merge_requests')
            ->where('user_sender_id', $userId)
            ->where('status', MergeStatus::Complete->value) // Status = Complete
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('user_receiver_id')
            ->toArray();

        // Merge the connected user IDs into the allowed IDs
        $allowedCreatorIds = array_merge($allowedCreatorIds, $connectedUserIds);
       // Log::info("the all alowed user are:" . json_encode($allowedCreatorIds));
        return $allowedCreatorIds;
    }

    public function message()
    {
        return "this person is not your own!";
    }
}
