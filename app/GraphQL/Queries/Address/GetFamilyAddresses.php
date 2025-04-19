<?php

namespace App\GraphQL\Queries\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetFamilyAddresses
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;

    protected $userId;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveFamilyAddress($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $query = $this->getModelByAuthorization(Address::class, $args, true, true);
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}