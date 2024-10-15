<?php

namespace App\GraphQL\Queries\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetClan
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
        $Clan = Clan::where('id', $args['id']);       
        return $Clan->first();
    }
}