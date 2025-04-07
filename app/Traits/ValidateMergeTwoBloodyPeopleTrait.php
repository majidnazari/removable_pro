<?php
namespace App\Traits;

use App\Models\Person;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;

trait ValidateMergeTwoBloodyPeopleTrait
{
    public function validatePersonsForMerge(Person $primaryPerson, Person $secondaryPerson): void
    {
        $allBloodPeopleOfPrimaryPerson = $this->getAllBloodPersonsInClanFromHeadsAccordingPersonId($primaryPerson->id, 10);
        Log::info("allBloodyPeopleOfPrimaryPerson are:" . json_encode($allBloodPeopleOfPrimaryPerson));

        $allBloodPeopleOfSecondaryPerson = $this->getAllBloodPersonsInClanFromHeadsAccordingPersonId($secondaryPerson->id, 10);
        Log::info("allBloodyPeopleOfSecondaryPerson are:" . json_encode($allBloodPeopleOfSecondaryPerson));

        $primarySet = collect($allBloodPeopleOfPrimaryPerson)->sort()->values()->all();
        $secondarySet = collect($allBloodPeopleOfSecondaryPerson)->sort()->values()->all();

        if ($primarySet !== $secondarySet) {
            throw new Error('Cannot merge: Primary and Secondary belong to different family.');
        }

        $primaryParents = $primaryPerson->getParents(personId: $primaryPerson->id);
        $secondaryParents = $secondaryPerson->getParents($secondaryPerson->id);

        $primaryFatherId = $primaryParents['father']?->id;
        $primaryMotherId = $primaryParents['mother']?->id;
        $secondaryFatherId = $secondaryParents['father']?->id;
        $secondaryMotherId = $secondaryParents['mother']?->id;

        if (
            !$primaryFatherId || !$primaryMotherId ||
            !$secondaryFatherId || !$secondaryMotherId
        ) {
            throw new \Error('Cannot merge: Both persons must have a mother and a father defined for validation.');
        }
        if (
            $primaryFatherId !== $secondaryFatherId ||
            $primaryMotherId !== $secondaryMotherId
        ) {
            throw new Error('Cannot merge: Primary and Secondary have different parents.');
        }

        Log::info("Primary and Secondary persons are in the same family and have same parents.");
    }
}
