<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonChild;
use App\Models\PersonMarriage;
use Illuminate\Support\Facades\Log;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\PersonAncestryHeads;

trait GetAllBloodPersonsWithSpousesInClanFromHeads
{
    use PersonAncestryWithCompleteMerge;
    use PersonAncestryHeads;

    /**
     * Recursively fetch all person IDs from descendants.
     */
    public function getAllBloodPersonIdsWithSpousesFromDescendants(int $personId, array &$visited = []): array
    {
        // Prevent infinite loops
        if (in_array($personId, $visited)) {
            return [];
        }

        $visited[] = $personId;
        $allPersonIds = [$personId];

        // Fetch person
        $person = Person::find($personId);
        if (!$person) {
            return $allPersonIds;
        }

        // Determine gender-based marriage column
        $marriageColumn = $person->gender == 1 ? 'man_id' : 'woman_id';

        // Get marriages where the person is involved
        $marriages = PersonMarriage::where($marriageColumn, $personId)->get();
        foreach ($marriages as $marriage) {
            $allPersonIds[] = $marriage->man_id;
            $allPersonIds[] = $marriage->woman_id;
        }

        // Get all children linked to these marriages
        $childrenIds = PersonChild::whereIn('person_marriage_id', $marriages->pluck('id'))
            ->pluck('child_id')
            ->unique()
            ->toArray();

        // Add children IDs
        $allPersonIds = array_merge($allPersonIds, $childrenIds);

        // Recursively fetch children's descendants
        foreach ($childrenIds as $childId) {
            $allPersonIds = array_merge($allPersonIds, $this->getAllBloodPersonIdsWithSpousesFromDescendants($childId, $visited));
        }

        return array_unique($allPersonIds);
    }

    /**
     * Get all person IDs in the clan from the heads.
     */
    public function getAllBloodPersonsWithSpousesInClanFromHeads($user_id, $depth = 10): array
    {

        $visited = [];
        $allPersonIds = [];

        $ancestryData = $this->getPersonAncestryHeads($user_id, 10);

        if (!$ancestryData || !isset($ancestryData['heads']) || empty($ancestryData['heads'])) {
            Log::warning("Ancestry data is null or empty for user ID: {$user_id}");
            return $allPersonIds; // Return null instead of throwing an error
        }

        $heads = collect($ancestryData['heads'])->pluck('person_id')->toArray();

        foreach ($heads as $head) {
            $descendants = $this->getAllBloodPersonIdsWithSpousesFromDescendants($head, $visited);
            $allPersonIds = array_merge($allPersonIds, $descendants);
        }

        return array_values(array_unique($allPersonIds));
    }
}
