<?php

namespace App\GraphQL\Queries\TalentHeader;

use App\Models\TalentHeader;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\GetsPeopleInGroups;
use App\Traits\FindOwnerTrait;

use App\GraphQL\Enums\Status;


final class GetSpecialTalentHeaders
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    use GetsPeopleInGroups;
    use FindOwnerTrait;
    protected $userId;
    protected $personOwner;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveTalentHeaders($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        $this->personOwner = $this->findOwner();

        // Start with the base query for 'deleted_at' and 'person_id'
        $query = TalentHeader::where('deleted_at', null)
            ->where('status', Status::Active->value)
            ->where('person_id', $args['person_id'] ?? $this->personOwner->id);

        // Apply the 'hasNoScores' filter if provided
        if (isset($args['hasNoScores']) && $args['hasNoScores']) {
            // Ensure we fetch TalentHeaders where at least one TalentDetail does not have a TalentDetailScore
            $query->whereHas('TalentDetails', function ($query) {
                // $query->whereDoesntHave('TalentDetailScores');  // This ensures we have at least one TalentDetail with no score
                $query->whereDoesntHave('TalentDetailScores', function ($scoreQuery) {
                    // Check if the TalentDetail has no score for the given participating_user_id
                    $scoreQuery->where('participating_user_id', $this->userId);
                });
            });
        }
        // Log::info("the query is:" . json_encode($query->get()));
        if (isset($args['person_id']) && $args['person_id'] != $this->personOwner->id) {

            // Condition 2: For another person_id, apply the additional checks
            $query->where(function ($subQuery) {
                // First check: creator_id matches logged-in user
                $subQuery->where('creator_id', $this->userId)
                    ->orWhere(function ($innerQuery) {
                        // Step 1: Check if the logged-in user exists in the GroupDetails relationship directly
                        $innerQuery->whereHas('GroupCategory.GroupCategoryDetails.Group.GroupDetails.UserCanSee', function ($groupDetailsQuery) {
                            // Step 2: Check if the logged-in user_id exists in the group_details
                            $groupDetailsQuery->where('id', $this->userId);


                        });
                    });
            });
        }

        // Fetch and log the talentheader
        $talentheader = $query;

        // Now we need to filter the TalentDetailScores based on the logged-in user
        // $talentheader->with([
        //     'TalentDetails' => function ($query) {
        //         $query->with([
        //             'TalentDetailScores' => function ($scoreQuery) {
        //                 // Filter scores by the logged-in userId
        //                 $scoreQuery->where('participating_user_id', $this->userId);
        //             }
        //         ]);
        //     }
        // ]);

        return $talentheader;
    }

}