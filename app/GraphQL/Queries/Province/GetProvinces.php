<?php

namespace App\GraphQL\Queries\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetProvinces
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
        $Provinces = Province::where('deleted_at', null);
        return $Provinces;
    }
}