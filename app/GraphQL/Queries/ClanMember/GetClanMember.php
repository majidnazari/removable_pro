<?php

namespace App\GraphQL\Queries\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetClanMember
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
        $ClanMember = ClanMember::where('id', $args['id']);       
        return $ClanMember->first();
    }
}