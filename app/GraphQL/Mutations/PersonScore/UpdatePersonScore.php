<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdatePersonScore
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
        //args["user_id_creator"]=$user_id;
        $PersonScoreResult=PersonScore::find($args['id']);
        $PersonScoremodel=$args;
        $PersonScoremodel['editor_id']=1;
        
        if(!$PersonScoreResult)
        {
            return Error::createLocatedError("PersonScore-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $PersonScoreResult_filled= $PersonScoreResult->fill($PersonScoremodel);
        $PersonScoreResult->save();       
       
        return $PersonScoreResult;

        
    }
}