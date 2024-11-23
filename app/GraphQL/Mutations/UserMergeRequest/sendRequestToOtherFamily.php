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
use Illuminate\Support\Facades\Auth;
use Exception;

final class SendRequestToOtherFamily
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

        $user_sender_id = Auth::guard('api')->user();

        if (!$user_sender_id) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        // Validate inputs using the custom validator
        //$this->validate($args);

        // Fetch the sender person
        $person = Person::find($args['node_sender_id']);

        // Fetch the receiver user by mobile
        $user_reciver = User::where('mobile', $person->country_code . $person->mobile)
            ->where('status', Status::Active)
            ->first();

        // Fetch the receiver's owner
        $person_reciver_owner = Person::where('creator_id', $user_reciver->id)
            ->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        // Prepare data for creating UserMergeRequest
        $UserMergeRequestResult = [
            "creator_id" => $user_sender_id,
            "user_sender_id" => $user_sender_id,
            "node_sender_id" => $args['node_sender_id'],
            "user_reciver_id" => $user_reciver->id,
            "node_reciver_id" => $person_reciver_owner->id,
            "status" => MergeStatus::Active,
            "request_sender_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "request_status_sender" => RequestStatusSender::Active,
        ];

        // Create the UserMergeRequest
        return UserMergeRequest::create($UserMergeRequestResult);
    }
}