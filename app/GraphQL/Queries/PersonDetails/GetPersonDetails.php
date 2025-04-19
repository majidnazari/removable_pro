<?php

namespace App\GraphQL\Queries\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


use Log;

final class GetPersonDetails
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
    function resolvePersonDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $query = $this->getModelByAuthorization(PersonDetail::class, $args, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}