<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteFamilyEvent
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
        $FamilyEventResult=FamilyEvent::find($args['id']);
        
        if(!$FamilyEventResult)
        {
            return Error::createLocatedError("FamilyEvent-DELETE-RECORD_NOT_FOUND");
        }
        $FamilyEventResult->editor_id= $user_id;
        $FamilyEventResult->save();

        $FamilyEventResult_filled= $FamilyEventResult->delete();  
        return $FamilyEventResult;

        
    }
}