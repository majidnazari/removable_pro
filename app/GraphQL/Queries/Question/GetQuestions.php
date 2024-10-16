<?php

namespace App\GraphQL\Queries\Question;

use App\Models\Question;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetQuestions
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveQuestion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Questions = Question::where('deleted_at', null);

        //log::info("the Scores is:" . json_encode($Questions));
        return $Questions;
    }
}