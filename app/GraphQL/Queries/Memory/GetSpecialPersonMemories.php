<?php

namespace App\GraphQL\Queries\Memory;

use App\Models\Memory;
use App\Models\GroupDetail;
use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\GetsPeopleInGroups;
use App\Traits\FindOwnerTrait;

use Log;


final class GetSpecialPersonMemories
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
    function resolveMemory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $memory = Memory::with([
            'GroupCategory.GroupCategoryDetails.Group.GroupDetails.UserCanSee' // Eager load the necessary relationships
        ])
            ->find(2);
        Log::info("memory with with: " . json_encode($memory));

        // Retrieve distinct user_ids from the related group_details
        $userIds = $memory->GroupCategory
            ->GroupCategoryDetails
            ->flatMap(function ($gcd) {
                return $gcd->Group->GroupDetails->pluck('user_id'); // Get all user_ids from group_details for each group
            })
            ->unique(); // Make sure to get unique user_ids

        // Log or return the user_ids
        Log::info("Distinct user_ids: " . json_encode($userIds));

        $this->userId = $this->getUserId();
        $this->personOwner = $this->findOwner();

        // Start with the base query for 'deleted_at' and 'person_id'
        $query = Memory::where('deleted_at', null)
            ->where('person_id', $this->personOwner->id);

        Log::info("the query is:" . json_encode($query->get()));
        if ($args['person_id'] == $this->personOwner->id) {
            // Condition 1: If the person_id matches personOwner's id, return all memories for that person_id
        } else {
            // Condition 2: For another person_id, apply the additional checks
            $query->where(function ($subQuery) {
                // First check: creator_id matches logged-in user
                $subQuery->where('creator_id', $this->userId)
                    ->orWhere(function ($innerQuery) {
                        // Step 1: Check if the logged-in user exists in the GroupDetails relationship directly
                        $innerQuery->whereHas('GroupCategory.GroupCategoryDetails.Group.GroupDetails', function ($groupDetailsQuery) {
                            // Step 2: Check if the logged-in user_id exists in the group_details
                            $groupDetailsQuery->where('user_id', $this->userId);
                        });
                    });
            });
        }

        // Fetch and log the memories
        $memories = $query;

        Log::info("person of this user is: " . json_encode($memories));

        return $memories;
    }




}