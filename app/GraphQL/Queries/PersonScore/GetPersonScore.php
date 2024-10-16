<?php

namespace App\GraphQL\Queries\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetPersonScore
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolvePersonScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $PersonScore = PersonScore::where('id', $args['id']);       
        return $PersonScore->first();
    }
}