<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateNaslanRelationship


{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $NaslanRelationshipResult=NaslanRelationship::find($args['id']);
        
        if(!$NaslanRelationshipResult)
        {
            return Error::createLocatedError("NaslanRelationship-UPDATE-RECORD_NOT_FOUND");
        }
        $NaslanRelationshipResult_filled= $NaslanRelationshipResult->fill($args);
        $NaslanRelationshipResult->save();       
       
        return $NaslanRelationshipResult;

        
    }
}