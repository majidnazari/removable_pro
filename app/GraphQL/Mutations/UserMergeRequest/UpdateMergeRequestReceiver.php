<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusReceiver;
use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\RequestStatusSender;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;

use Exception;

final class UpdateMergeRequestReceiver
{
    use AuthUserTrait;
    use AuthorizesMutation;

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
        $this->user_receiver_id = $this->getUserId();
    
       $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);

        $data = [
           // "editor_id" => $user_sender_id,
            "merge_status_receiver" => $args['merge_status_receiver'] ?? RequestStatusReceiver::Suspend->value
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

        if($UserMergeRequest->merge_status_sender != RequestStatusSender::Active->value && ($args['merge_status_receiver'] === RequestStatusReceiver::Active->value)){
            return Error::createLocatedError("UserMergeRequest-FIRST_MERGE_SENDER_MUST_MAKE_REQUEST_ACTIVE" );

        } 
        if(( $args['merge_status_receiver'] == RequestStatusSender::Active->value) &&
           ( $UserMergeRequest->request_status_sender != RequestStatusSender::Active->value ||
            $UserMergeRequest->request_status_receiver != RequestStatusSender::Active->value ||
            $UserMergeRequest->merge_status_sender != RequestStatusSender::Active->value  )
            ){
            return Error::createLocatedError("UserMergeRequest-ALL_STATUS_MUST_BE_ACTIVE" );

        } 

          $UserMergeRequestResult = $UserMergeRequest->fill($data);
          $UserMergeRequestResult->save();       

        return $UserMergeRequestResult;
    }
}