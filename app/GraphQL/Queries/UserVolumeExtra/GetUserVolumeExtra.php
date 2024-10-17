<?php

namespace App\GraphQL\Queries\UserVolumeExtra;

use App\Models\UserVolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetUserVolumeExtra
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
        $UserVolumeExtra = UserVolumeExtra::where('id', $args['id']);       
        return $UserVolumeExtra->first();
    }
}