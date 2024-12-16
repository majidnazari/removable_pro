<?php

namespace App\GraphQL\Mutations\Notif;

use App\Models\Notif;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;
use Log;

final class CreateNotif
{
    use  AuthUserTrait;
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
        

        $NotifResult=[
            // "status" => $args['status'] ?? Status::Active,
            // "title" => $args['title'],
        ];
        $is_exist= Notif::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Notif-CREATE-RECORD_IS_EXIST");
         }
        $NotifResult_result=Notif::create($NotifResult);
        return $NotifResult_result;
    }
}