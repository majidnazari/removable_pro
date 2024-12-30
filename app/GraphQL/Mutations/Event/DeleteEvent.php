<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteEvent
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
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

        try {

            $EventResult = $this->userAccessibility(Event::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($EventResult, $args, $this->userId);
    //    $this->userAccessibility(Event::class, AuthAction::Delete, $args);

  
    //     $EventResult=Event::find($args['id']);
        
    //     if(!$EventResult)
    //     {
    //         return Error::createLocatedError("Event-DELETE-RECORD_NOT_FOUND");
    //     }
    //     //$args['editor_id']=$user_id;
    //     $EventResult->editor_id=  $this->userId;
    //     $EventResult->save();
    //     $EventResult_filled= $EventResult->delete();  
    //     return $EventResult;

        
    }
}