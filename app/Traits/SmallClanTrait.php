<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Exceptions\CustomValidationException;


trait SmallClanTrait
{
    use AuthUserTrait;
    use FindOwnerTrait;

    protected $allPersonIds = [];
    protected $allOwnerPersonIds = [];

    public function getAllPeopleIdsSmallClan($personId)
    {
        $person = Person::where('id', $personId)->where('status', Status::Active->value)->first();
        if (!$person)
            throw new CustomValidationException("Person with ID {$personId} not found in the clan.", "شخص با شناسه {$personId} در خانواده یافت نشد.", 400);

        if ($person->is_owner)
            return $person->id;

        $userId = $this->getUserId();
        $owner = $this->findOwner($userId);

        $parentIds = $this->getParentIdsSmallClan($person->id);

        $spouseIds = $this->getSpouseIdsSmallClan($person->id, $person->gender);

        $childrenIds = $this->getChildrenIdsSmallClan($spouseIds, $person->gender);

        $this->allPersonIds = collect([$person->id, $spouseIds, $childrenIds, $parentIds])
            ->flatten()->unique()->values()->all();

        return $this->allPersonIds;
    }


    protected function getSpouseIdsSmallClan($personId, $isMale)
    {
        $getSpouseIdsSmallClan = PersonMarriage::where($isMale ? 'man_id' : 'woman_id', $personId)
            ->where('status', Status::Active)
            ->pluck($isMale ? 'woman_id' : 'man_id')
            ->toArray();

        return $getSpouseIdsSmallClan;
    }

    protected function getChildrenIdsSmallClan(array $spouseIds, $isMale)
    {
        if (empty($spouseIds)) {
            return [];
        }

        // Ensure $spouseIds is an array with valid values
        $spouseIds = array_filter($spouseIds);

        // Ensure at least one valid ID exists before querying
        if (empty($spouseIds)) {
            return [];
        }

        $peronMarriageIds = PersonMarriage::whereIn($isMale ? 'woman_id' : 'man_id', $spouseIds)
            ->where('status', Status::Active)
            ->pluck('id')
            ->toArray();

        if (empty($peronMarriageIds)) {
            return [];
        }

        $getChildrenIdsSmallClan = PersonChild::whereIn('person_marriage_id', $peronMarriageIds)
            ->pluck('child_id')
            ->toArray();

        return $getChildrenIdsSmallClan;
    }



    protected function getParentIdsSmallClan($personId)
    {
        $parentIds = [];

        // Find the parent-child relationship
        $parent = PersonChild::where('child_id', $personId)->first();
        if (!$parent) {
            return $parentIds; // Return empty array if no parent record
        }

        // Find the parent's marriage
        $parentMarriage = PersonMarriage::where('id', $parent->person_marriage_id)
            ->where('status', Status::Active)
            ->first();

        if ($parentMarriage) {
            $parentIds = [$parentMarriage->man_id, $parentMarriage->woman_id];
        } else {
            //           Log::info("No active marriage found for parent relationship: " . json_encode($parent));
        }

        return $parentIds;
    }


    public function getOwnerIdSmallClan($personId)
    {
        return Person::where('id', $personId)->where('is_owner', true)->first();
    }






    public function getAllOwnerIdsSmallClan($personId)
    {
        $allPeopleIds = $this->getAllPeopleIdsSmallClan($personId);
        if (!is_array($allPeopleIds))
            $allPeopleIds = [$allPeopleIds];

        $this->allOwnerPersonIds = Person::whereIn('id', $allPeopleIds)
            ->where('is_owner', true)
            ->pluck('id')->toArray();

        //       Log::info(" getAllOwnerIdsSmallClan in small clan: " . json_encode($this->allOwnerPersonIds));

        return $this->allOwnerPersonIds;
    }

    public function getAllUserIdsSmallClan($personId)
    {
        $allOwnerIds = $this->getAllOwnerIdsSmallClan($personId);
        $allUsersInSmallClan = Person::whereIn('id', $allOwnerIds)->where('status', Status::Active)
            ->pluck('creator_id')->toArray();

        //       Log::info(" getAllOwnerIdsSmallClan in small clan: " . json_encode($allUsersInSmallClan));

        return $allUsersInSmallClan;

    }

}
