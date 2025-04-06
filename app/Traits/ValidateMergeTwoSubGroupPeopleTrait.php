<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\GetAllBloodPersonsInClanFromHeadsAccordingPersonId;

trait ValidateMergeTwoSubGroupPeopleTrait
{
    use GetAllBloodPersonsInClanFromHeadsAccordingPersonId;

    /**
     * Validates if the two persons can be merged into the same subgroup.
     *
     * @param int $primaryId
     * @param int $secondaryId
     * @throws \Exception
     */
    public function validateSubGroupMerge(int $primaryId, int $secondaryId): void
    {
        $visitedPrimary = [];
        $visitedSecondary = [];

        // Get all blood-related persons (descendants) for each
        $primaryGroup = $this->getAllBloodPersonIdsFromDescendantsAccordingPersonId($primaryId, $visitedPrimary);
        $secondaryGroup = $this->getAllBloodPersonIdsFromDescendantsAccordingPersonId($secondaryId, $visitedSecondary);

        Log::info("Primary group: " . json_encode($primaryGroup));
        Log::info("Secondary group: " . json_encode($secondaryGroup));

        // Check for intersection
        $intersect = array_intersect($primaryGroup, $secondaryGroup);

        if (!empty($intersect)) {
            Log::warning("Cannot merge: common people found: " . json_encode($intersect));
            throw new Exception("Cannot merge: one or both persons belong to another subgroup.");
        }
    }
}
