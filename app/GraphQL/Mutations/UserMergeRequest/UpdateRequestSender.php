<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\RequestStatusSender;
use App\GraphQL\Enums\MergeStatus;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;

final class UpdateRequestSender
{
    use AuthUserTrait;
    protected $user_sender_id;

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
        $this->user_sender_id = $this->getUserId();

        $data = [
            "editor_id" =>  $this->user_sender_id,
            "request_status_sender" => $args['request_status_sender'] ?? RequestStatusSender::Suspend
        ];

        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
       //->where('request_status_sender',  RequestStatusSender::Active->value)
        
        if (!$UserMergeRequest) {
            return Error::createLocatedError("UserSendRequest-NOT_FOUND");
        }

        $is_exist = UserMergeRequest::where('user_sender_id',  $this->user_sender_id)
            ->where('id','!=', $args['id'])
            // ->where('user_receiver_id', $user_receiver->id)
            ->where('request_status_sender',  RequestStatusSender::Active->value)
            ->where('status', '!=', MergeStatus::Complete->value)
            ->first();

        if ($is_exist) {
            return Error::createLocatedError("UserSendRequest-YOU_HAVE_ONE_ACTIVE_REQUEST");
        }

        if($UserMergeRequest->creator_id !=  $this->user_sender_id){
            return Error::createLocatedError("UserSendRequest-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS");

        }
        $UserMergeRequestResult = $UserMergeRequest->fill($data);
        $UserMergeRequestResult->save();       

        return $UserMergeRequestResult;
    }
}