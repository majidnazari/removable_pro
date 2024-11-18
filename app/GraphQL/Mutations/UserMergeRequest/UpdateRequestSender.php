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
use App\GraphQL\Enums\RequestStatusSender;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Log;

final class UpdateRequestSender
{

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUpdateRequestSender($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $user_sender_id = auth()->guard('api')->user()->id;

        $data = [
            "editor_id" => $user_sender_id,
            "request_status_sender" => $args['request_status_sender'] ?? RequestStatusSender::Suspend
        ];

        Log::info("the update status sender :". json_encode($data));

       // Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
       //->where('request_status_sender',  RequestStatusSender::Active->value)
        
        if (!$UserMergeRequest) {
            return Error::createLocatedError("UserMergeRequest-NOT_FOUND");
        }

        $is_exist = UserMergeRequest::where('user_sender_id', $user_sender_id)
            ->where('id','!=', $args['id'])
            // ->where('user_reciver_id', $user_reciver->id)
            ->where('request_status_sender',  RequestStatusSender::Active->value)
            ->first();

        if ($is_exist) {
            return Error::createLocatedError("UserMergeRequest-YOU_HAVE_ONE_ACTIVE_REQUEST");
        }

        if($UserMergeRequest->creator_id != $user_sender_id){
            return Error::createLocatedError("UserMergeRequest-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS");

        }
        $UserMergeRequestResult = $UserMergeRequest->fill($data);
        $UserMergeRequestResult->save();       

        return $UserMergeRequestResult;
    }
}