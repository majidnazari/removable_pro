<?php

namespace App\GraphQL\Queries\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetNaslanRelationships
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $NaslanRelationships = NaslanRelationship::where('deleted_at', null);

        //log::info("the NaslanRelationships is:" . json_encode($NaslanRelationships));
        return $NaslanRelationships;
    }
}