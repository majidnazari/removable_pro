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

final class SendRequestToOtherFamily
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

        $user_sender_id = auth()->guard('api')->user()->id;

        // if ($user_sender_id === $args['user_sender_id']) {
        //     return Error::createLocatedError("the sender and reciver cannot be the same!");
        // }

        $person = Person::where('id', $args['node_sender_id'])->first();

        if ($person && empty($person->country_code) && empty($person->mobile)) {
            return Error::createLocatedError("the person with this mobile not found!");
        }

        $user_reciver = User::where('mobile', $person->country_code . $person->mobile)
            //->where('is_owner',true)
            //->where(column: 'country_code',operator: $args['country_code'])
            // ->where('id', '!=', $user_sender_id)
            //->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        if (!$user_reciver) {
            return Error::createLocatedError("the node you have seleted not found!");
        }

        //Log::info("the creator id node input is:" . $person->creator_id . "and the user id sender is:" .  $user_sender_id );
        if ($person->creator_id !==$user_sender_id ) {
            return Error::createLocatedError("you don't have access to other family!");
        }
        if ($user_reciver->id === $user_sender_id) {
            return Error::createLocatedError("the sender and reciver cannot be the same!");
        }


        $person_reciver_owner = Person::where('creator_id', $user_reciver->id)
        ->where('is_owner',true)
        ->where('status',Status::Active)
        ->first();

        //Log::info("the person_reciver_owner are:" . $person_reciver_owner);

        if (!$person_reciver_owner) {
            return Error::createLocatedError("the person reciver owner not found!");
        }



        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $UserMergeRequestResult = [
            "creator_id" => $user_sender_id,
            "user_sender_id" => $user_sender_id,
            "node_sender_id" => $args['node_sender_id'],
            "user_reciver_id" => $user_reciver->id,
            "node_reciver_id" => $person_reciver_owner->id,
            "status" => MergeStatus::Active,

            "request_sender_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "request_status_sender" => RequestStatusSender::Active
        ];

        // Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $is_exist = UserMergeRequest::where('user_sender_id', $user_sender_id)
            // ->where('node_sender_id', $args['node_sender_id'])
            // ->where('user_reciver_id', $user_reciver->id)
            ->where('request_status_sender',  RequestStatusSender::Active->value)
            ->first();
       // Log::info("the args are:" . json_encode(  $is_exist) . " and user id is :". $user_sender_id);

        if ($is_exist) {
            return Error::createLocatedError("UserMergeRequest-YOU_HAVE_ONE_ACTIVE_REQUEST");
        }
        $UserMergeRequestResult_result = UserMergeRequest::create($UserMergeRequestResult);
        return $UserMergeRequestResult_result;
    }
}