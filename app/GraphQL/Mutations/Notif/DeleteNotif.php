<?php

namespace App\GraphQL\Mutations\Notif;

use App\Models\Notif;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;

use Exception;
final class DeleteNotif
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
    public function resolveNotif($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        try {

            $NotifResult = $this->userAccessibility(Notif::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($NotifResult, $args, $this->userId);
    //    $this->userAccessibility(Notif::class, AuthAction::Delete, $args);
       
    //     $NotifResult=Notif::find($args['id']);
        
    //     if(!$NotifResult)
    //     {
    //         return Error::createLocatedError("Notif-DELETE-RECORD_NOT_FOUND");
    //     }
    //     $NotifResult->editor_id= $this->userId;
    //     $NotifResult->save(); 


    //     $NotifResult_filled= $NotifResult->delete();  
    //     return $NotifResult;

        
    }
}