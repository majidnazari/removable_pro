<?php

namespace App\GraphQL\Queries\Memory;

use App\GraphQL\Enums\ConfirmMemoryStatus;
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
            ->where('confirm_status', ConfirmMemoryStatus::Accept->value)
            ->where('person_id', $args['person_id'] ?? $this->personOwner->id);

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

        // Fetch and log the memories
        $memories = $query;
        return $memories;
    }

}