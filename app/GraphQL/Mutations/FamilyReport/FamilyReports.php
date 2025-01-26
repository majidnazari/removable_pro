<?php

namespace App\GraphQL\Mutations\FamilyReport;

use App\Models\FamilyReport;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Carbon\Carbon;

use Log;

final class FamilyReports
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;

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
        $clanId = Person::where('id', $this->userId)->value('clan_id');

        if (!$clanId) {
            throw new Error("User does not belong to any clan.");
        }

        // Check if a FamilyReport already exists for this clan_id
        $familyReport = FamilyReport::where('clan_id', $clanId)->first();

        if (!$familyReport) {
            // If no report exists, create one and populate it
            $familyReport = $this->generateFamilyReport($clanId);
        }

        return $familyReport;
    }
    private function generateFamilyReport($clanId)
    {
        // Initialize counters
        $menCount = 0;
        $womenCount = 0;
        $oldest = null;
        $youngest = null;
        $marriageCount = 0;
        $divorceCount = 0;

        // Get all the people from the clan
        $people = Person::where('clan_id', $clanId)->where('status', Status::Active)->get();

        foreach ($people as $person) {
            // Increment gender count
            if ($person->gender === 'male') {
                $menCount++;
            } elseif ($person->gender === 'female') {
                $womenCount++;
            }

            // Find the oldest and youngest
            if (!$oldest || $person->birthdate < $oldest) {
                $oldest = $person->birthdate;
            }
            if (!$youngest || $person->birthdate > $youngest) {
                $youngest = $person->birthdate;
            }

            // Count marriages
            $marriageCount += PersonMarriage::where(function ($query) use ($person) {
                $query->where('man_id', $person->id)
                    ->orWhere('woman_id', $person->id);
            })->count();

            // Count divorces
            $divorceCount += PersonMarriage::where(function ($query) use ($person) {
                $query->where('man_id', $person->id)
                    ->orWhere('woman_id', $person->id);
            })->where('status', 'divorced')->count();
        }

        // Create the FamilyReport record
        $familyReport = FamilyReport::create([
            'clan_id' => $clanId,
            'men_count' => $menCount,
            'women_count' => $womenCount,
            'oldest' => $oldest ? Carbon::parse($oldest)->year : null,
            'youngest' => $youngest ? Carbon::parse($youngest)->year : null,
            'marriage_count' => $marriageCount,
            'divorce_count' => $divorceCount,
            'change_flag' => true,
            'updated_at' => Carbon::now(),
        ]);

        return $familyReport;
    }

}