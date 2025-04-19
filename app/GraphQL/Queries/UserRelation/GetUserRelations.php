<?php

namespace App\GraphQL\Queries\UserRelation;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\GraphQL\Enums\Role;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;

final class GetUserRelations
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
    function resolveUser($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = $this->getModelByAuthorization(User::class, $args, true);
        // Apply search filters and ordering
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}