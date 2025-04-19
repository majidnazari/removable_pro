<?php

namespace App\GraphQL\Queries\MajorField;

use App\Models\MajorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetMajorFields
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
    function resolveMajorField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = $this->getModelByAuthorization(MajorField::class, $args, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}