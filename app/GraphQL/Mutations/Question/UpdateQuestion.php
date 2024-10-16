<?php

namespace App\GraphQL\Mutations\Question;

use App\Models\Question;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateQuestion
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveQuestion($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $QuestionResult=Question::find($args['id']);
        
        if(!$QuestionResult)
        {
            return Error::createLocatedError("Question-UPDATE-RECORD_NOT_FOUND");
        }
        $QuestionResult_filled= $QuestionResult->fill($args);
        $QuestionResult->save();       
       
        return $QuestionResult;

        
    }
}