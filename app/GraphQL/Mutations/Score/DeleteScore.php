<?php

namespace App\GraphQL\Mutations\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteScore
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $ScoreResult=Score::find($args['id']);
        
        if(!$ScoreResult)
        {
            return Error::createLocatedError("Score-DELETE-RECORD_NOT_FOUND");
        }
        $ScoreResult_filled= $ScoreResult->delete();  
        return $ScoreResult;

        
    }
}