<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateFamilyEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $FamilyEventResult=FamilyEvent::find($args['id']);
        
        if(!$FamilyEventResult)
        {
            return Error::createLocatedError("FamilyEvent-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $FamilyEventResult_filled= $FamilyEventResult->fill($args);
        $FamilyEventResult->save();       
       
        return $FamilyEventResult;

        
    }
}