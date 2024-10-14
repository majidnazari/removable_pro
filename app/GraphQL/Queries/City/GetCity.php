<?php

namespace App\GraphQL\Queries\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetCity
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
        $City = City::where('id', $args['id']);       
        return $City->first();
    }
}