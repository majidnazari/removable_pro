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
use Illuminate\Support\Facades\DB;

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
        $max_longevity = 0;
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
        $womenquery = clone $query;
        $womenCount = $womenquery->where('gender', 0)->count();
        $menquery = clone $query;
        $menCount = $menquery->where('gender', 1)->count();


        // Clone the base query
        $max_longevityquery = clone $query;
        $max_longevityquery_dead = clone $query;

        $maxLongevityPerson = $max_longevityquery->select(
            'id',
            DB::raw('CASE 
                        WHEN death_date IS NOT NULL THEN TIMESTAMPDIFF(YEAR, birth_date, death_date)
                        ELSE TIMESTAMPDIFF(YEAR, birth_date, NOW())
                    END AS age')
        )
            ->orderByDesc('age') // Order by age descending to get the oldest person first
            ->first();
            if($maxLongevityPerson)
            {
                $max_longevity=$maxLongevityPerson->age;
            }
        Log::info("final max_longevity_person is :" . json_encode($maxLongevityPerson));


        $oldestquery = clone $query;
        // Get the oldest LIVING person (death_date is NULL)
        $oldest_person = $oldestquery->whereNotNull('birth_date')->whereNull('death_date')->orderBy('birth_date', 'asc')->first();

        $oldestYear = $oldest_person ? Carbon::parse($oldest_person->birth_date)->diffInYears(Carbon::now()->format("Y-m-d H:i:s")) : null;

        // Youngest person
        $youngestquery = clone $query;

        $youngest = $youngestquery->orderBy('birth_date', 'desc')->first(); // Latest birth_date
        // Log::info("the youngest is : " . Carbon::parse($youngest->birth_date)->year);

        $youngestAge = $youngest ? (int) Carbon::parse($youngest->birth_date)->diffInYears(Carbon::now()->format("Y-m-d H:i:s")) : null;


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
                'oldest' => (int) $oldestYear,
                'max_longevity' => (int) $max_longevity,
                'youngest' => (int) $youngestAge,
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
                'oldest' => (int) $oldestYear,
                'max_longevity' => (int) $max_longevity,
                'youngest' => (int) $youngestAge,
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