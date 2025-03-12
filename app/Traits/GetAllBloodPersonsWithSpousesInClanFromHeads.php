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
        // $ancestryData = $this->getPersonAncestryWithCompleteMerge($user_id, $depth);
        // $heads = collect($ancestryData['heads'])->pluck('person_id')->toArray();

        // Log::info("Heads found: " . json_encode($heads));

        $ancestryData= $this->getPersonAncestryHeads($user_id,10);
        $heads = collect($ancestryData['heads'])->pluck('person_id')->toArray();
        Log::info("headstmp found: " . json_encode($heads));

        $visited = [];
        $allPersonIds = [];

        // Fetch descendants for each head
        foreach ($heads as $head) {
            $descendants = $this->getAllBloodPersonIdsWithSpousesFromDescendants($head, $visited);
            $allPersonIds = array_merge($allPersonIds, $descendants);
        }

        //Log::info("All people in clan: " . json_encode(array_unique($allPersonIds)));
        Log::info("All people in clan: " . json_encode(array_values(array_unique($allPersonIds))));


        return array_values(array_unique($allPersonIds));
    }
}
