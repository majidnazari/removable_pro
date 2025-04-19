<?php

namespace App\GraphQL\Queries\MinorField;

use App\Models\MinorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetMinorFields
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
    function resolveMinorField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $query = $this->getModelByAuthorization(MinorField::class, $args, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}