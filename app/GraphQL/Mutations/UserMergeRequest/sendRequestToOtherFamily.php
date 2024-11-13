<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\RequestStatus;
use App\models\user;
use Carbon\Carbon;
use Log;

final class sendRequestToOtherFamily
{
   
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        $user=User::where('mobile',$args['mobile'])
        //->where('is_owner',true)
        ->where('status',Status::Active)
        ->first();


        Log::info("the args are:" . json_encode( $user) . " and user id is :". $user->id. " and the carbo is:" .Carbon::now()->addDays(1)->format("Y-M-d H:i:s"));


        if(!$user)
        {
            return Error::createLocatedError("the user not found");
        }
        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $UserMergeRequestResult=[
            "sender_id" => $args['sender_id'] ,
            "reciver_id" => $user->id,
            "request_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "request_is_read"=> 0,
            "request_status" => RequestStatus::Susspend       
        ];

        Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $is_exist= UserMergeRequest::where('sender_id',$args['sender_id'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("UserMergeRequest-CREATE-RECORD_IS_EXIST");
         }
        $UserMergeRequestResult_result=UserMergeRequest::create($UserMergeRequestResult);
        return $UserMergeRequestResult_result;
    }
}