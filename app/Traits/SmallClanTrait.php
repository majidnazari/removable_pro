<?php

namespace App\Traits;

use App\Models\Person;
use App\Models\Memory;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use GraphQL\Error\Error;
use Exception;
use Log;

trait SmallClanTrait
{
    use AuthUserTrait;
    use FindOwnerTrait;

    protected $userId;
    protected $person;
    protected $owner;
    protected $allPersonIds = [];
    protected $allOwnerPersonIds = [];

    public function getAllPeopleIdsSmallClan($personId )
    {
        $this->person=Person::where('id',$personId)->where('status',Status::Active)->first();

        Log::info("getAllPeopleIdsSmallClan the person is :" . json_encode($this->person));
        if(!$this->person)
        {
            return -1;
        }
        if( $this->person->is_owner)
        {
            return $this->person->id;
        }

        // If a userId is provided, set the userId
        // $this->userId = $this->person->creator_id;
         $this->userId = $this->getUserId();

        // Find the owner (the person initiating the query)
        $this->owner = $this->findOwner( $this->userId );

        // Get spouse ids and add to allPersonIds
        $spouseIds = $this->getSpouseIdsSmallClan($this->person->id, $this->person->gender);
        Log::info("getAllPeopleIdsSmallClan the spouseIds are  :" . json_encode($spouseIds));


        // Get children ids and add to allPersonIds
        $childrenIds = $this->getChildrenIdsSmallClan($spouseIds);

        Log::info("getAllPeopleIdsSmallClan the childrenIds are  :" . json_encode($childrenIds));


        // Get parent ids and add to allPersonIds
        $parentIds = $this->getParentIdsSmallClan($this->person->id);
        Log::info("getAllPeopleIdsSmallClan the parentIds are  :" . json_encode($parentIds));


        // Merge all ids (including owner) and remove duplicates
        $this->allPersonIds = collect([$this->person->id])
            ->merge($spouseIds)
            ->merge($childrenIds)
            ->merge($parentIds)
            ->unique()
            ->values()
            ->all();

        Log::info("getAllPeopleIdsSmallClan  allPersonIds are: " . json_encode($this->allPersonIds));

        return $this->allPersonIds;
    }

    public function getAllChildrenSmallClan($personId)
    {
       
        Log::info("the getAllChildrenSmallClan personId:". json_encode($personId));

       $person=Person::where('id',$personId)->where('status',Status::Active)->first();
        // Get spouse ids and add to allPersonIds
        $spouseIds =  PersonMarriage::where(($person->gender==1) ? 'man_id' : 'woman_id', $personId)
        ->where('status', Status::Active)
        ->first()->id;

        // Get children ids and add to allPersonIds
        $childrenIds = $this->getChildrenIds([$spouseIds]);
        return  $childrenIds;
    }

    public function getAllOwnerIdsSmallClan($personId )
    {
        Log::info("the getAllOwnerIdsSmallClan personId:". json_encode($personId));

        $this->allOwnerPersonIds=[];
        $allPeopleIdsSmallClan=$this->getAllPeopleIdsSmallClan($personId);
        Log::info("getAllOwnerIdsSmallClan and allPeopleIdsSmallClan are:". json_encode($allPeopleIdsSmallClan));
        //$num=is_array(value: $allPeopleIdsSmallClan) ? count($allPeopleIdsSmallClan): 0;
        if (is_null($allPeopleIdsSmallClan)) {
            // Handle null case
            Log::info("getAllOwnerIdsSmallClan No people found in the small clan.");
            $allPeopleIdsSmallClan = []; // Convert null to an empty array to prevent errors
        } elseif (!is_array($allPeopleIdsSmallClan)) {

            Log::info("getAllOwnerIdsSmallClan No people found in the small clan.");

            // If it's a single integer, convert it to an array
            $allPeopleIdsSmallClan = [$allPeopleIdsSmallClan];
        }
        
        // Now, $allPeopleIdsSmallClan is always an array, and you can safely use it
        Log::info("getAllOwnerIdsSmallClan People IDs in small clan: " .  json_encode($allPeopleIdsSmallClan));
       

        Log::info("the allPeopleIdsSmallClan is array:". (is_array($allPeopleIdsSmallClan) ));
        Log::info("the allPeopleIdsSmallClan count:". (count( $allPeopleIdsSmallClan)));
        //$allpeopleIds=$this->getAllPeopleIdsSmallClan();
        if(is_array($allPeopleIdsSmallClan)  && count( $allPeopleIdsSmallClan)>=1)
        {
            Log::info("inside if ");
            $this->allOwnerPersonIds = Person::whereIn('id',$allPeopleIdsSmallClan)->where('is_owner', true)->pluck('id');

            Log::info("the all owners are :" .json_encode($this->allOwnerPersonIds));

        }
        // else if (collect($this->allPeopleIdsSmallClan)->isNotEmpty()){
        //     Log::info("inside else ");

        //     $this->allOwnerPersonIds = Person::where('id',$allPeopleIdsSmallClan)->where('is_owner', true)->pluck('id');
        //     Log::info("the all owners are :" .json_encode($this->allOwnerPersonIds));

        // }
        Log::info("the all owneres ids are " . json_encode($this->allOwnerPersonIds));
        return $this->allOwnerPersonIds->toArray();
    }
    
    public function getOwnerIdSmallClan($personId )
    {
        //$allpeopleIds=$this->getAllPeopleIdsSmallClan();
        $this->owner= Person::where('id', $personId)->where('is_owner', true)->first();
        Log::info("the all owneres ids are " . json_encode($this->allOwnerPersonIds));
        return $this->owner;
    }

    public function getAllUserIdsSmallClan($personId )
    {
        $this->allUserIds=[];
        $allOwnerIdsSmallClan=$this->getAllOwnerIdsSmallClan($personId);
        Log::info("the person id is: " . $personId. "  the allOwnerIdsSmallClanxx are:". json_encode($allOwnerIdsSmallClan));
        //$num=is_array(value: $allOwnerIdsSmallClan) ? count($allOwnerIdsSmallClan): 0;
        //$allpeopleIds=$this->getAllPeopleIdsSmallClan();
        Log::info(" is array of allUserIds :" . is_array($allOwnerIdsSmallClan) . " and count is :" .count( value: $allOwnerIdsSmallClan)  );

        if(is_array($allOwnerIdsSmallClan)  &&  count( value: $allOwnerIdsSmallClan)>1)
        {
            $this->allUserIds = Person::whereIn('id',  $allOwnerIdsSmallClan)->where('status', Status::Active->value)->pluck('creator_id');
             Log::info(" allUserIds in count bigger than 1 :" . json_encode($this->allUserIds));


        }
        else if (collect($allOwnerIdsSmallClan)->isNotEmpty()){
            $this->allUserIds = Person::where('id',  $allOwnerIdsSmallClan)->where('status', Status::Active->value)->pluck('creator_id');

            Log::info(" allUserIds in count  is 1 or ziro :" . json_encode($this->allUserIds));


        }
        Log::info("the all users ids are " . json_encode($this->allUserIds));
        return $this->allUserIds;
    }

    /**
     * Get spouse ids based on the owner's gender.
     *
     * @param int $ownerId
     * @param bool $isMale
     * @return array
     */
    protected function getSpouseIdsSmallClan($ownerId, $isMale)
    {
        $getSpouseIds= PersonMarriage::where($isMale ? 'man_id' : 'woman_id', $ownerId)
            ->where('status', Status::Active)
            //->pluck($isMale ? 'woman_id' : 'man_id')
            ->pluck('id')
            ->toArray();

            Log::info("the getSpouseIds in small clan is :" . json_encode($getSpouseIds));
            return $getSpouseIds;
    }

    /**
     * Get the child ids of the given marriages.
     *
     * @param array $spouseIds
     * @return array
     */
    protected function getChildrenIdsSmallClan(array $spouseIds)
    {
        $getChildrenIds= PersonChild::where('person_marriage_id', $spouseIds)
            ->pluck('child_id')
            ->toArray();
            Log::info("the getChildrenIds in small clan is :" . json_encode($getChildrenIds));

            return $getChildrenIds;
    }

    /**
     * Get the parent ids of the given owner.
     *
     * @param int $ownerId
     * @return array
     */
    protected function getParentIdsSmallClan($ownerId)
    {
        $parentIds = [];

        // Find the parent's marriage and add their ids
        $parent = PersonChild::where('child_id', $ownerId)->first();

        if ($parent) {
            $parentMarriage = PersonMarriage::where('id', $parent->person_marriage_id)
                ->where('status', Status::Active)
                ->first();

            if ($parentMarriage) {
                $parentIds = [
                    $parentMarriage->man_id,
                    $parentMarriage->woman_id,
                ];
            }
        }
        Log::info("the getParentIds in small clan is :" . json_encode($parentIds));


        return $parentIds;
    }


}
