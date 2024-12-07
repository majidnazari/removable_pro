<?php

namespace App\GraphQL\Mutations\Notif;

use App\Models\Notif;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

use Exception;
final class DeleteNotif
{
    use AuthUserTrait;
    use checkMutationAuthorization;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveNotif($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(Notif::class, AuthAction::Delete, $args);
       
        $NotifResult=Notif::find($args['id']);
        
        if(!$NotifResult)
        {
            return Error::createLocatedError("Notif-DELETE-RECORD_NOT_FOUND");
        }
        $NotifResult->editor_id= $this->userId;
        $NotifResult->save(); 


        $NotifResult_filled= $NotifResult->delete();  
        return $NotifResult;

        
    }
}