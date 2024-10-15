<?php

namespace App\GraphQL\Queries\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetArea
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
        $Area = Area::where('id', $args['id']);       
        return $Area->first();
    }
}