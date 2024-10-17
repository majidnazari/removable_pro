<?php

namespace App\GraphQL\Queries\UserVolumeExtra;

use App\Models\UserVolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetUserVolumeExtras
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveUserVolumeExtra($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $UserVolumeExtras = UserVolumeExtra::where('deleted_at', null);

        //log::info("the UserVolumeExtras is:" . json_encode($UserVolumeExtras));
        return $UserVolumeExtras;
    }
}