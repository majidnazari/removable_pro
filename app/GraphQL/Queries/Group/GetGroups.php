<?php

namespace App\GraphQL\Queries\Group;

use App\Models\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetGroups
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
    function resolveGroup($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $query = $this->getModelByAuthorization(Group::class, $args, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}