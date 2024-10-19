<?php

namespace App\GraphQL\Queries\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetNaslanRelationship
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
        $NaslanRelationship = NaslanRelationship::where('id', $args['id']);       
        return $NaslanRelationship->first();
    }
}