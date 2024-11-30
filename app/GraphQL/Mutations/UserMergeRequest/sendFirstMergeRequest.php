<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\RequestStatus;
use App\models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;
use Log;

final class sendFirstMergeRequest
{
    use AuthUserTrait;
    protected $userId;
   
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

        $this->userId = $this->getUserId();

        // $user=User::where('mobile',$args['mobile'])
        // //->where('is_owner',true)
        // ->where('status',Status::Active)
        // ->first();


        // Log::info("the args are:" . json_encode( $user) . " and user id is :". $user->id);


        // if(!$user)
        // {
        //     return Error::createLocatedError("the user not found");
        // }
        // //Log::info("the args are:" . json_encode($args));
        // $user = Auth::guard('api')->user();

        // if (!$user) {
        //     throw new Exception("Authentication required. No user is currently logged in.");
        // }
        // $data=[
        //     "merge_sender_id" => $args['sender_id'] ,
        //     "merge_receiver_id" => $user->id,
        //     "merge_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
        //     "merge_is_read"=> 0,
        //     "merge_status" => RequestStatus::Suspend       
        // ];

        // $UserMergeRequestResult=UserMergeRequest::where('id',3)->first();
        // $UserMergeRequestResult->fill($data);
        // $UserMergeRequestResult->save();
        // return $UserMergeRequestResult;
    }
}