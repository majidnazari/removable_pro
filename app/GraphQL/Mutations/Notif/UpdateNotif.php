<?php

namespace App\GraphQL\Mutations\Notif;

use App\Models\Notif;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use App\Traits\HandlesModelUpdateAndDelete;


use Exception;

final class UpdateNotif
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
    public function resolveNotif($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();

        try {

            $NotifResult = $this->userAccessibility(Notif::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // $this->checkDuplicate(
        //     new Notif(),
        //     $args,
        //     ['id', 'editor_id', 'created_at', 'updated_at'],
        //     excludeId: $args['id']
        // );

        return $this->updateModel($NotifResult, $args, $this->userId);


    //    $this->userAccessibility(Notif::class, AuthAction::Update, $args);
        
    //     $NotifResult=Notif::find($args['id']);
        
    //     if(!$NotifResult)
    //     {
    //         return Error::createLocatedError("Notif-UPDATE-RECORD_NOT_FOUND");
    //     }
    //    // $args['editor_id']=$this->userId;
        
    //     $NotifResult_filled= $NotifResult->fill($args);
    //     $NotifResult->save();       
       
    //     return $NotifResult;

        
    }
}