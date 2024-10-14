<?php

namespace App\GraphQL\Queries\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetCities
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveCity($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Cities = City::where('deleted_at', null);
        return $Cities;
    }
}