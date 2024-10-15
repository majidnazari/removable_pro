<?php

namespace App\GraphQL\Queries\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetAreas
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveArea($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $areas = Area::where('deleted_at', null)->with("City");
        return $areas;
    }
}