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

final class sendFirstMergeRequest
{
   
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvesendFirstMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        $user=User::where('mobile',$args['mobile'])
        //->where('is_owner',true)
        ->where('status',Status::Active)
        ->first();


        Log::info("the args are:" . json_encode( $user) . " and user id is :". $user->id);


        if(!$user)
        {
            return Error::createLocatedError("the user not found");
        }
        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $data=[
            "merge_sender_id" => $args['sender_id'] ,
            "merge_reciver_id" => $user->id,
            "merge_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "merge_is_read"=> 0,
            "merge_status" => RequestStatus::Susspend       
        ];

        // Log::info("the args are:" . json_encode($UserMergeRequestResult));
        // $is_exist= UserMergeRequest::where('sender_id',$args['sender_id'])->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("UserMergeRequest-CREATE-RECORD_IS_EXIST");
        //  }
        $UserMergeRequestResult=UserMergeRequest::where('id',3)->first();
        $UserMergeRequestResult->fill($data);
        $UserMergeRequestResult->save();
        return $UserMergeRequestResult;
    }
}