<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomValidationException;

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

//       Log::info("Primary group: " . json_encode($primaryGroup));
//       Log::info("Secondary group: " . json_encode($secondaryGroup));

        // Check for intersection
        if (in_array($primaryId, $secondaryGroup)) {
            Log::warning("Cannot merge: Primary ID {$primaryId} exists in secondary group.");
            //throw new Exception("Cannot merge: Primary person already belongs to the secondary's subgroup.");
            throw new CustomValidationException("Cannot merge: Primary person already belongs to the secondary's subgroup.", "نمی توان ادغام کرد: فرد اصلی از قبل به زیر گروه شخص دوم تعلق دارد.", 400);


        }

        if (in_array($secondaryId, $primaryGroup)) {
            Log::warning("Cannot merge: Secondary ID {$secondaryId} exists in primary group.");
           // throw new Exception("Cannot merge: Secondary person already belongs to the primary's subgroup.");
            throw new CustomValidationException("Cannot merge: Secondary person already belongs to the primary's subgroup.", "نمی توان ادغام کرد: شخص دوم از قبل به زیر گروه اصلی تعلق دارد.", 400);

        }

//       Log::info("Subgroups are independent. Merge is allowed.");
    }
}
