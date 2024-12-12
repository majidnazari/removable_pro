<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusSender;
use App\GraphQL\Enums\RequestStatusReceiver;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


use Exception;
use Log;

final class SendMergeRequestToOtherFamily
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
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();
    
       $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);
        // Fetch the sender person
        $userMergeRequest = UserMergeRequest::where('id',$args['id'])
        //->where('status',MergeStatus::Active)
        ->where('request_status_sender',  RequestStatusSender::Active->value)
        ->where('request_status_receiver',  RequestStatusReceiver::Active->value)
        ->first();

        if(!$userMergeRequest)
        {
            return Error::createLocatedError("UserMergeRequest-USER_MERGE_REQUEST_NOT_FOUND!");
        }
       
        // Prepare data for creating UserMergeRequest
        $data= [
           
            'merge_ids_sender' => $args['merge_ids_sender'],
            'merge_ids_receiver' => $args['merge_ids_receiver'],
            "merge_sender_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "merge_status_sender" => RequestStatusSender::Active,
            //"status" => MergeStatus::Active,

        ];

        $userModelRequestResult= $userMergeRequest->fill($data);
        $userModelRequestResult->save();
        // Create the UserMergeRequest
        return $userModelRequestResult;
    }
}