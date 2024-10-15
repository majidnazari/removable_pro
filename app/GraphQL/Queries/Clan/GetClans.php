<?php

namespace App\GraphQL\Queries\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetClans
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveClan($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $clans = Clan::where('deleted_at', null);
        return $clans;
    }
}