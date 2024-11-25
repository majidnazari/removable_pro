<?php

namespace App\GraphQL\Queries\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;


use Log;

final class GetPersonScores
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
    function resolvePersonScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $PersonScores = PersonScore::where('deleted_at', null);

        //log::info("the Scores is:" . json_encode($PersonScores));
        return $PersonScores;
    }
}