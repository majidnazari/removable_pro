<?php

namespace App\GraphQL\Queries\Question;

use App\Models\Question;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetQuestion
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
        $Question = Question::where('id', $args['id']);       
        return $Question->first();
    }
}