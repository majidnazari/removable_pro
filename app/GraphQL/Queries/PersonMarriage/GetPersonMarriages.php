<?php

namespace App\GraphQL\Queries\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetPersonMarriages
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
    function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = $this->getModelByAuthorization(PersonMarriage::class, $args, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}