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
use App\models\User;
use App\models\Person;
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

        $user_sender_id = auth()->guard('api')->user()->id;

        if ($user_sender_id === $args['user_sender_id']) {
            return Error::createLocatedError("the sender and reciver cannot be the same!");
        }

        $person = Person::where('id', $args['node_sender_id'])->first();

        if ($person && empty($person->country_code) && empty($person->mobile)) {
            return Error::createLocatedError("the person with this mobile not found please try again.");
        }

        $user_reciver = User::where('mobile', $person->country_code . $person->mobile)
            //->where('is_owner',true)
            //->where(column: 'country_code',operator: $args['country_code'])
           // ->where('id', '!=', $user_sender_id)
            //->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        if ($user_reciver->id === $user_sender_id) {
            return Error::createLocatedError("the sender and reciver cannot be the same!");
        }
        //Log::info("the args are:" . json_encode( $user) . " and user id is :". $user->id. " and the carbo is:" .Carbon::now()->addDays(1)->format("Y-M-d H:i:s"));

        if (!$user_reciver) {
            return Error::createLocatedError("the node you have seleted not found!");
        }

       


        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $UserMergeRequestResult = [
            "user_sender_id" => $user_sender_id,
            "node_sender_id" => $args['node_sender_id'],
            "user_reciver_id" => $user_reciver->id,
            // "node_reciver_id" => $args['node_reciver_id'],

            "request_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "request_is_read" => 0,
            "request_status" => RequestStatus::Susspend
        ];

        Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $is_exist = UserMergeRequest::where('user_sender_id', $user_sender_id)
            ->where('node_sender_id', $args['node_sender_id'])
            ->where('user_reciver_id', $user_reciver->id)
            ->where('request_status', RequestStatus::Susspend)
            ->first();
        if ($is_exist) {
            return Error::createLocatedError("UserMergeRequest-CREATE-RECORD_IS_EXIST");
        }
        $UserMergeRequestResult_result = UserMergeRequest::create($UserMergeRequestResult);
        return $UserMergeRequestResult_result;
    }
}