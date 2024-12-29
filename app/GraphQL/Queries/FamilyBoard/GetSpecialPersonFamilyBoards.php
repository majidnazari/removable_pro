<?php

namespace App\GraphQL\Queries\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\GraphQL\Enums\Status;


final class GetSpecialPersonFamilyBoards
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        //$this->personOwner = $this->findOwner();

        // Start with the base query for 'deleted_at' and 'person_id'
        $query = FamilyBoard::where('deleted_at', null) 
            ->where('status', Status::Active->value);
        // Log::info("the query is:" . json_encode($query->get()));
        $query->where(function ($subQuery) {
            // Step 1: Check if the logged-in user exists in the GroupDetails relationship directly
            $subQuery->whereHas('GroupCategory.GroupCategoryDetails.Group.GroupDetails.UserCanSee', function ($groupDetailsQuery) {
                // Step 2: Check if the logged-in user_id exists in the group_details
                $groupDetailsQuery->where('id', $this->userId);


            });
        });

        // Fetch and log the memories
        $boards = $query;

        // Log::info("pall memories can this user see are : " . json_encode($memories->get()));

        return $boards;
    }
}
