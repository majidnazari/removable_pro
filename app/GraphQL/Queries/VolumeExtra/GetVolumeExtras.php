<?php

namespace App\GraphQL\Queries\VolumeExtra;

use App\Models\VolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetVolumeExtras
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
        $VolumeExtras = VolumeExtra::where('deleted_at', null);

        //log::info("the VolumeExtras is:" . json_encode($VolumeExtras));
        return $VolumeExtras;
    }
}