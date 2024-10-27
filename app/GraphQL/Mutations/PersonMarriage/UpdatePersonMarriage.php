<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdatePersonMarriage
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        $PersonMarriagemodel=$args;
        $PersonMarriagemodel['editor_id']=1;
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-UPDATE-RECORD_NOT_FOUND");
        }
        $PersonMarriageResult_filled= $PersonMarriageResult->fill($PersonMarriagemodel);
        $PersonMarriageResult->save();       
       
        return $PersonMarriageResult;

        
    }
}