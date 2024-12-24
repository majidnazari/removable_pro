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
        $this->userId = $this->getUserId();
        $this->personOwner = $this->findOwner();

        // Start with the base query for 'deleted_at' and 'person_id'
        $query = Memory::where('deleted_at', null)
            ->where('person_id', $this->personOwner->id);

        if ($args['person_id'] == $this->personOwner->id) {
            // Condition 1: If the person_id matches personOwner's id, return all memories for that person_id
        } else {
            // Condition 2: For another person_id, apply the additional checks
            $query->where(function ($subQuery) {
                // First check: creator_id matches logged-in user
                $subQuery->where('creator_id', $this->userId)
                    ->orWhere(function ($innerQuery) {
                        // Second check: Check if the logged-in user exists in the group_category_id relation
    
                        // Step 1: Get the list of group_category_ids from group_category_details
                        $groupCategoryIds = GroupCategoryDetail::whereHas('GroupCategory', function ($groupCategoryQuery) {
                            // Step 2: Get all group_ids from the group_category
                            $groupCategoryQuery->whereHas('GroupCategoryDetails', function ($groupCategoryDetailsQuery) {
                                // Step 3: For each group_category_detail, check if the logged-in user exists in group_details
                                $groupCategoryDetailsQuery->whereHas('Group', function ($groupQuery) {
                                    $groupQuery->whereHas('GroupDetails', function ($detailsQuery) {
                                        // Step 4: Check if the logged-in user_id exists in group_details
                                        $detailsQuery->where('user_id', $this->userId);
                                    });
                                });
                            });
                        })->pluck('group_category_id'); // Get a list of group_category_ids
    
                        // Step 5: Filter memories where the group_category_id matches one of the fetched ids
                        $innerQuery->whereIn('group_category_id', $groupCategoryIds);
                    });
            });
        }

        // Fetch and log the memories
        $memories = $query;

        Log::info("person of this user is: " . json_encode($memories));

        return $memories;
    }




}