<?php

namespace App\GraphQL\Queries\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetClanMembers
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveClanMember($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $ClanMembers = ClanMember::where('deleted_at', null);
        return $ClanMembers;
    }
}