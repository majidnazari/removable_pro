<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonChild;
use App\Models\PersonMarriage;
use Illuminate\Support\Facades\Log;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\AuthUserTrait;
use App\Traits\PersonAncestryHeads;


trait GetAllBloodUsersRelationInClanFromHeads
{

    use PersonAncestryWithCompleteMerge;
    use AuthUserTrait;
    use PersonAncestryHeads;
    /**
     * Get all descendants (children, grandchildren, etc.) of a person recursively.
     */
    public function getAllBloodUserIdsFromDescendants(int $personId, array &$visited = []): array
    {
        // Initialize an empty array to store user IDs
        $userIds = [];

        // Check if the person has already been visited to prevent infinite loops
        if (in_array($personId, $visited)) {
            Log::warning("Skipping already visited descendant: $personId");
            return $userIds;
        }

        // Mark this person as visited
        $visited[] = $personId;
        //       Log::info("Fetching descendants for person: $personId");

        // Get the person's record from the database
        $person = Person::find($personId);
        if (!$person) {
            Log::error("Person not found with ID: $personId");
            return $userIds;
        }

        // Check if the person is an owner and add their user_id
        if ($person->is_owner == 1) {
            $userIds[] = $person->creator_id;
            //           Log::info("Person $personId is an owner, creator_id: " . $person->creator_id);
        }

        // Determine the correct column to use based on gender
        $marriageColumn = $person->gender == 1 ? 'man_id' : 'woman_id';

        // Get all marriage IDs where the person is involved
        $personMarriageIds = PersonMarriage::where($marriageColumn, $personId)
            ->pluck('id')
            ->toArray();

        if (empty($personMarriageIds)) {
            //           Log::info("No marriages found for person: $personId");
            return $userIds;
        }

        //       Log::info("Marriages found for person $personId: " . implode(', ', $personMarriageIds));

        // Get all children associated with these marriages
        $childrenIds = PersonChild::whereIn('person_marriage_id', $personMarriageIds)
            ->pluck('child_id')
            ->unique()
            ->toArray();

        if (empty($childrenIds)) {
            //           Log::info("No children found for person: $personId");
            return $userIds;
        }

        //       Log::info("Children found for person $personId: " . implode(', ', $childrenIds));

        // Merge descendants by recursively calling the function for each child
        foreach ($childrenIds as $childId) {
            $userIds = array_merge($userIds, $this->getAllBloodUserIdsFromDescendants($childId, $visited));
        }

        return array_unique($userIds);  // Return unique user IDs
    }

    public function getAllBloodUsersInClanFromHeads($user_id, $depth = 10)
    {
        $user = $this->getUser();

        // Fetch result from getPersonAncestryHeads
        $result = $this->getPersonAncestryHeads($user->id, $depth);

        // Check if 'heads' exists in the result and is not null
        if (isset($result['heads']) && !empty($result['heads'])) {
            $heads = collect($result['heads'])->pluck('person_id')->toArray();
            //           Log::info("Heads found: " . json_encode($heads));
        } else {
            // If 'heads' is null or empty, handle gracefully
            $heads = [];
            //           Log::info("Heads is null or empty.");
        }

        // Initialize visited array to track processed IDs
        $visited = [];

        // Initialize array to store all user IDs
        $allUserIds = [];

        // For each head (starting person), fetch their descendants
        foreach ($heads as $head) {
            // Call the method with an initialized $visited array
            $descendants = $this->getAllBloodUserIdsFromDescendants($head, $visited);

            // Merge the returned user IDs into the final array
            $allUserIds = array_merge($allUserIds, $descendants);

            //           Log::info("Descendants for person $head: " . json_encode($descendants));
        }

        // Remove duplicates and return the final list of user IDs
        $allUserIds = array_unique($allUserIds);

        return $allUserIds;
    }

}
