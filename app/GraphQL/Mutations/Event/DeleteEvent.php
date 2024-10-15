<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $EventResult=Event::find($args['id']);
        
        if(!$EventResult)
        {
            return Error::createLocatedError("Event-DELETE-RECORD_NOT_FOUND");
        }
        $EventResult_filled= $EventResult->delete();  
        return $EventResult;

        
    }
}