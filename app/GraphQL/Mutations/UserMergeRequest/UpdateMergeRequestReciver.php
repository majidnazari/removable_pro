<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusReceiver;
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
use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdateMergeRequestReceiver
{
    protected $user_receiver_id;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUpdateMergeRequestreceiver($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $user_receiver = Auth::guard('api')->user();

        if (!$user_receiver) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->user_receiver_id = $user_receiver->id;

        $data = [
           // "editor_id" => $user_sender_id,
            "request_status_receiver" => $args['request_status_receiver'] ?? RequestStatusReceiver::Suspend->value
        ];

       // Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
        if (!$UserMergeRequest) {
            return Error::createLocatedError("UserMergeRequest-NOT_FOUND");
        }

        $is_exist = UserMergeRequest::where('user_receiver_id', $this->user_receiver_id)
        ->where('id','!=', $args['id'])
        // ->where('user_receiver_id', $user_receiver->id)
        ->where('request_status_sender',  RequestStatusSender::Active->value)
        ->where('request_status_receiver',  RequestStatusReceiver::Active->value)
        ->where('merge_status_sender',  RequestStatusSender::Active->value)
        ->first();

        if ($is_exist) {
            return Error::createLocatedError("UserMergeRequest-YOU_HAVE_ONE_ACTIVE_REQUEST");
        }

        if($UserMergeRequest->user_receiver_id != $this->user_receiver_id){
            return Error::createLocatedError("UserMergeRequest-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS");

        }


        //Log::info("the active sttaus us:".RequestStatusSender::Active->value);
        if($UserMergeRequest->request_status_sender != RequestStatusSender::Active->value){
            return Error::createLocatedError("UserMergeRequest-FIRST_SENDER_MUST_MAKE_REQUEST_ACTIVE" );

        }

        if($UserMergeRequest->merge_status_sender != RequestStatusSender::Active->value){
            return Error::createLocatedError("UserMergeRequest-FIRST_MERGE_SENDER_MUST_MAKE_REQUEST_ACTIVE" );

        } 

          $UserMergeRequestResult = $UserMergeRequest->fill($data);
          $UserMergeRequestResult->save();       

        return $UserMergeRequestResult;
    }
}