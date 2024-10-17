<?php

namespace App\GraphQL\Queries\VolumeExtra;

use App\Models\VolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetVolumeExtra
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveVolumeExtra($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $VolumeExtra = VolumeExtra::where('id', $args['id']);       
        return $VolumeExtra->first();
    }
}