<?php

namespace App\GraphQL\Mutations\FamilyReport;

use App\GraphQL\Enums\MarriageStatus;
use App\Models\FamilyReport;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\GetAllowedAllUsersInClan;
use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Models\User;
use Carbon\Carbon;

use Log;

final class FamilyReports
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllowedAllUsersInClan;

    protected $userId;
    protected $users;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyReport($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        // Retrieve the user's clan_id from the User table
        $clanId = User::where('id', $this->userId)->first()->clan_id;
        $this->users = $this->getAllowedUserIds();

        if (!$clanId) {
            throw new Error("User does not belong to any clan.");
        }

        // Check if a FamilyReport already exists for this clan_id
        $familyReport = FamilyReport::where('clan_id', $clanId)->first();

        if (!$familyReport) {
           // Log::info("the create1 is running ");

            // If no report exists, create one and populate it
            $familyReport = $this->generateFamilyReport($clanId);
        }
        if (!$familyReport->change_flag) {
            //Log::info("the update1 is running ");

            $familyReport = $this->generateFamilyReport($clanId, $familyReport, true);
        }

        //Log::info("the familyReport is running " . json_encode($familyReport));

        return $familyReport;
    }
    private function generateFamilyReport($clanId, $familyReport = null, $flag = false)
    {
        $newfamilyReport = null;
        // Initialize counters
        $menCount = 0;
        $womenCount = 0;
        $oldest = 0;
        $oldest_dead = 0;
        $youngest = 0;
        $marriageCount = 0;
        $divorceCount = 0;

        // Get all the people from the clan
        $people = Person::whereIn('creator_id', $this->users)->where('status', Status::Active)->get();

        // Initialize the base query to get people in the clan
        $query = Person::whereIn('creator_id', $this->users)
            ->where('status', Status::Active);

        // Men count
    

        // Women count
        $womenquery= clone $query ;
        $womenCount = $womenquery->where('gender', 0)->count();
        $menquery= clone $query ;
        $menCount = $menquery->where('gender', 1)->count();


        // Oldest person
        $oldestquery= clone $query ;

        $oldest = $oldestquery->orderBy('birth_date', 'asc')->first(); // Earliest birth_date
        $oldestYear =  $oldest ? (int)Carbon::parse($oldest->birth_date)->diffInYears(Carbon::now()) : null;
       

          // oldest_dead person
          $oldest_deadquery= clone $query ;

          $oldestDead = $oldest_deadquery->whereNotNull('death_date')->orderBy('death_date', 'asc')->first();
          $oldest_dead =  $oldestDead ? (int)Carbon::parse($oldestDead->birth_date)->diffInYears(Carbon::parse($oldestDead->death_date)) : null;

          
        // Youngest person
        $youngestquery= clone $query ;

        $youngest = $youngestquery->orderBy('birth_date', 'desc')->first(); // Latest birth_date
       // Log::info("the youngest is : " . Carbon::parse($youngest->birth_date)->year);

        $youngestAge = $youngest ? (int)Carbon::parse($youngest->birth_date)->diffInYears(Carbon::now()) : null;


        // Marriage count
        $marriageCount = PersonMarriage::whereIn('creator_id', $this->users)
            //->orWhereIn('woman_id', $query->pluck('id'))
            ->count();

        // Divorce count (assuming '2' represents divorce status)
        $divorceCount = PersonMarriage::whereIn('creator_id', $this->users)
           // ->orWhereIn('woman_id', $query->pluck('id'))
            ->where('marriage_status', 2) // Divorce status
            ->count();

        if ($flag) {
            //Log::info("the update is running ");
            $familyReport->update([
                'men_count' => $menCount,
                'women_count' => $womenCount,
                'oldest' => $oldestYear,
                'oldest_dead' => $oldest_dead,
                'youngest' => $youngestAge,
                'marriage_count' => $marriageCount,
                'divorce_count' => $divorceCount,
                'change_flag' => true,
                'last_update' => Carbon::now(),
            ]);
            $newfamilyReport = $familyReport;
        } else {
            //Log::info("the create is running ");

            // Create the FamilyReport record
            $newfamilyReport = FamilyReport::create([
                'clan_id' => $clanId,
                'men_count' => $menCount,
                'women_count' => $womenCount,
                'oldest' => $oldestYear,
                'oldest_dead' => $oldest_dead,
                'youngest' => $youngestAge,
                'marriage_count' => $marriageCount,
                'divorce_count' => $divorceCount,
                'change_flag' => true,
                'last_update' => Carbon::now(),
                'status' => Status::Active,
            ]);
        }


        return $newfamilyReport;
    }

}