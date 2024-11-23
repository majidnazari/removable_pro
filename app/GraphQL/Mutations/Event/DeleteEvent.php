<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteEvent
{
    protected $userId;

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
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;   
        $EventResult=Event::find($args['id']);
        
        if(!$EventResult)
        {
            return Error::createLocatedError("Event-DELETE-RECORD_NOT_FOUND");
        }
        //$args['editor_id']=$user_id;
        $EventResult->editor_id=  $this->userId;
        $EventResult->save();
        $EventResult_filled= $EventResult->delete();  
        return $EventResult;

        
    }
}