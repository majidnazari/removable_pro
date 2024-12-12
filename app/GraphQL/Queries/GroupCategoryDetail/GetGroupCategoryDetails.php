<?php

namespace App\GraphQL\Queries\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetGroupCategoryDetails
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
    function resolveGroupCategoryDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $GroupCategoryDetails = GroupCategoryDetail::where('deleted_at', null);
        // return $GroupCategoryDetails;

        $query = $this->getModelByAuthorization(GroupCategoryDetail::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}