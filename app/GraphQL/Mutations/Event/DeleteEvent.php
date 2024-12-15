<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class DeleteEvent
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