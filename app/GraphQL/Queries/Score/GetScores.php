<?php

namespace App\GraphQL\Queries\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;

use Log;

final class GetScores
{
    use AuthUserTrait;
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
        $this->userId = $this->getUserId();

        $Scores = Score::where('deleted_at', null);

        //log::info("the Scores is:" . json_encode($Scores));
        return $Scores;
    }
}