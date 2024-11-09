<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeletePersonScore
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;        
        $PersonScoreResult=PersonScore::find($args['id']);
        
        if(!$PersonScoreResult)
        {
            return Error::createLocatedError("PersonScore-DELETE-RECORD_NOT_FOUND");
        }

        $PersonScoreResult->editor_id= $user_id;
        $PersonScoreResult->save(); 

        $PersonScoreResult_filled= $PersonScoreResult->delete();  
        return $PersonScoreResult;

        
    }
}