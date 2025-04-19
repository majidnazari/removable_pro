<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonChild;
use App\Models\PersonMarriage;
use Illuminate\Support\Facades\Log;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\PersonAncestryHeads;

trait GetAllBloodPersonsInClanFromHeads
{
    use PersonAncestryWithCompleteMerge;
    use PersonAncestryHeads;

    /**
     * Recursively fetch all person IDs from descendants.
     */
    public function getAllBloodPersonIdsFromDescendants(int $personId, array &$visited = []): array
    {
        // Prevent infinite loops
        if (in_array($personId, $visited)) {
            return [];
        }

        $visited[] = $personId;
        $allPersonIds = [$personId];

        // Determine gender-based marriage column
        $marriageColumn = Person::where('id', $personId)->value('gender') == 1 ? 'man_id' : 'woman_id';

        // Get marriages where the person is involved
        $marriageIds = PersonMarriage::where($marriageColumn, $personId)->pluck('id')->toArray();

        // Get all children linked to these marriages
        $childrenIds = PersonChild::whereIn('person_marriage_id', $marriageIds)
            ->pluck('child_id')
            ->unique()
            ->toArray();

        // Recursively fetch children's descendants
        foreach ($childrenIds as $childId) {
            $allPersonIds = array_merge($allPersonIds, $this->getAllBloodPersonIdsFromDescendants($childId, $visited));
        }

        return array_unique($allPersonIds);
    }

    /**
     * Get all person IDs in the clan from the heads.
     */
    public function getAllBloodPersonsInClanFromHeads($user_id, $depth = 10): array
    {

        $ancestryData = $this->getPersonAncestryHeads($user_id, 10);
        $heads = collect($ancestryData['heads'])->pluck('person_id')->toArray();

        $visited = [];
        $allPersonIds = [];

        // Fetch descendants for each head
        foreach ($heads as $head) {
            $descendants = $this->getAllBloodPersonIdsFromDescendants($head, $visited);
            $allPersonIds = array_merge($allPersonIds, $descendants);
        }

        return array_unique($allPersonIds);
    }
}
