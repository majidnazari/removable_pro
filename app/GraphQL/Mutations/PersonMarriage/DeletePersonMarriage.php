<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeletePersonMarriage
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
        $user_id=auth()->guard('api')->user()->id;        
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-DELETE-RECORD_NOT_FOUND");
        }
        if ($PersonMarriageResult->PersonChild()->exists()) 
        {
            return Error::createLocatedError("PersonMarriage-HAS_CHILDREN!");

        }

        $PersonMarriageResult->editor_id= $user_id;
        $PersonMarriageResult->save(); 

        $PersonMarriageResult_filled= $PersonMarriageResult->delete();  
        return $PersonMarriageResult;

        
    }
}