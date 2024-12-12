<?php

namespace App\GraphQL\Queries\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetGroupDetails
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
    function resolveGroupDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $GroupDetails = GroupDetail::where('deleted_at', null);
        // return $GroupDetails;

        $query = $this->getModelByAuthorization(GroupDetail::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}