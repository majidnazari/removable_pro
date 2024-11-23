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
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusSender;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Log;

final class SendMergeRequestToOtherFamily
{
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

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        // Validate inputs using the custom validator
        //$this->validate($args);

        // Fetch the sender person
        $userMergeRequest = UserMergeRequest::where('id',$args['id'])
        ->where('status',MergeStatus::Active)
        ->first();

        if(!$userMergeRequest)
        {
            return Error::createLocatedError("UserMergeRequest-USER_MERGE_REQUEST_NOT_FOUND!");
        }
       
        // Prepare data for creating UserMergeRequest
        $data= [
           
            'merge_ids_sender' => $args['merge_ids_sender'],
            'merge_ids_reciver' => $args['merge_ids_reciver'],
            "merge_sender_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "merge_status_sender" => RequestStatusSender::Active,
            "status" => MergeStatus::Active,

        ];

        $userModelRequestResult= $userMergeRequest->fill($data);
        $userModelRequestResult->save();
        // Create the UserMergeRequest
        return $userModelRequestResult;
    }
}