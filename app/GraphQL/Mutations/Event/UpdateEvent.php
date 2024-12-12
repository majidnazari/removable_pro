<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use App\Traits\AuthUserTrait;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;

final class UpdateEvent
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
        $this->userId = $this->getUserId();
       $this->userAccessibility(Event::class, AuthAction::Delete, $args);


        //args["user_id_creator"]=$user_id;
        $EventResult=Event::find($args['id']);
        
        if(!$EventResult)
        {
            return Error::createLocatedError("Event-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;

        $EventResult_filled= $EventResult->fill($args);
        $EventResult->save();       
       
        return $EventResult;

        
    }
}