<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonChild;
use App\Models\PersonMarriage;
use App\Models\UserRelation;
use Illuminate\Support\Facades\Log;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\AuthUserTrait;
use App\Traits\PersonAncestryHeads;
use App\Traits\UpdateUserRelationTrait;


trait GetAllUsersRelationInClanFromHeads
{
    use PersonAncestryWithCompleteMerge;
    use AuthUserTrait;
    use PersonAncestryHeads;
    use UpdateUserRelationTrait;

    /**
     * Get all descendants (children, grandchildren, etc.) of a person recursively, including their spouses.
     */
    public function getAllUserIdsFromDescendants(int $personId, array &$visited = []): array
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
        Log::info("Fetching descendants for person: $personId");

        // Get the person's record from the database
        $person = Person::find($personId);
        if (!$person) {
            Log::error("Person not found with ID: $personId");
            return $userIds;
        }

        // Check if the person is an owner and add their user_id
        if ($person->is_owner == 1) {
            $userIds[] = $person->creator_id;
            Log::info("Person $personId is an owner, creator_id: " . $person->creator_id);
        }

        // Determine the correct column to use based on gender
        $marriageColumn = $person->gender == 1 ? 'man_id' : 'woman_id';
        $spouseColumn = $person->gender == 1 ? 'woman_id' : 'man_id'; // Opposite gender to fetch spouse

        // Get all marriage IDs where the person is involved
        $personMarriageRecords = PersonMarriage::where($marriageColumn, $personId)->get();

        if ($personMarriageRecords->isEmpty()) {
            Log::info("No marriages found for person: $personId");
        } else {
            Log::info("Marriages found for person $personId: " . json_encode($personMarriageRecords->pluck('id')->toArray()));

            // Fetch all spouse IDs
            $spouseIds = $personMarriageRecords->pluck($spouseColumn)->unique()->toArray();

            if (!empty($spouseIds)) {
                Log::info("Spouses found for person $personId: " . json_encode($spouseIds));

                // Get all spouse records
                $spouses = Person::whereIn('id', $spouseIds)->get();

                foreach ($spouses as $spouse) {
                    if ($spouse->is_owner == 1) {
                        $userIds[] = $spouse->creator_id;
                        Log::info("Spouse $spouse->id is an owner, creator_id: " . $spouse->creator_id);
                    }
                }
            }
        }

        // Get all children associated with these marriages
        $personMarriageIds = $personMarriageRecords->pluck('id')->toArray();
        if (!empty($personMarriageIds)) {
            Log::info("personMarriageIds found : " . json_encode($personMarriageIds));
            $childrenIds = PersonChild::whereIn('person_marriage_id', $personMarriageIds)
                ->pluck('child_id')
                ->unique()
                ->toArray();
        }
        if (empty($childrenIds)) {
            Log::info("No children found for person: $personId");
            return array_unique($userIds);
        }

        Log::info("Children found for person $personId: " . json_encode($childrenIds));

        // Merge descendants by recursively calling the function for each child
        foreach ($childrenIds as $childId) {
            $userIds = array_merge($userIds, $this->getAllUserIdsFromDescendants($childId, $visited));
        }

        return array_unique($userIds);  // Return unique user IDs
    }

    public function getAllUsersInClanFromHeads($user_id, $depth = 10)
    {
        $user = $this->getUser();
        // If blood_user_relation_calculated is true, fetch from user_relations directly
        if ($user->blood_user_relation_calculated) {
            return $this->getAllUserRelation($user->id);
        }
        // $PersonAncestry = $this->getPersonAncestryWithCompleteMerge($user->id, $depth);
        // $heads = collect($PersonAncestry["heads"])->pluck("person_id")->toArray();

        // Log::info("The heads are: " . json_encode($heads));


        $result = $this->getPersonAncestryHeads($user->id, $depth);
        $heads = collect($result['heads'])->pluck('person_id')->toArray();
        Log::info("heads found: " . json_encode($heads));


        // Initialize visited array to track processed IDs
        $visited = [];

        // Initialize array to store all user IDs
        $allUserIds = [];

        // For each head (starting person), fetch their descendants
        foreach ($heads as $head) {
            // Call the method with an initialized $visited array
            $descendants = $this->getAllUserIdsFromDescendants($head, $visited);

            // Merge the returned user IDs into the final array
            $allUserIds = array_merge($allUserIds, $descendants);

            Log::info("Descendants for person $head: " . json_encode($descendants));
        }

        // Remove duplicates and return the final list of user IDs
        $allUserIds = array_unique($allUserIds);
        Log::info("Final list of user IDs before remove itself: " . json_encode($allUserIds));



        // Remove logged-in user ID from the list
        // $allUserIds = array_filter($allUserIds, function ($userId) use ($user) {
        //     return $userId != $user->id;
        // });
        // // Reindex array to fix the keys
        // $allUserIds = array_values($allUserIds);

        //Log::info("Final list of user IDs after remove itself: " . json_encode($allUserIds));

        return $this->calculateUserRelationInClan($allUserIds);

        //return $allUserIds;
    }

    public function getAllUserRelation($user_id)
    {
        return UserRelation::where('creator_id', $user_id)->pluck('related_with_user_id')->toArray();
    }
}
