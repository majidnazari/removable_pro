<?php

namespace App\GraphQL\Queries\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetProvince
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveProvince($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Province = Province::where('id', $args['id']);       
        return $Province->first();
    }
}