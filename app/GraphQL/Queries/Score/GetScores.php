<?php

namespace App\GraphQL\Queries\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetScores
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
        $Scores = Score::where('deleted_at', null);

        //log::info("the Scores is:" . json_encode($Scores));
        return $Scores;
    }
}