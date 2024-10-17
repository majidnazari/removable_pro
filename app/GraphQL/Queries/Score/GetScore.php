<?php

namespace App\GraphQL\Queries\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetScore
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Score = Score::where('id', $args['id']);       
        return $Score->first();
    }
}