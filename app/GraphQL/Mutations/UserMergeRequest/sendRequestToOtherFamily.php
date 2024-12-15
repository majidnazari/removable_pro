<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
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
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;


use Exception;

final class SendRequestToOtherFamily
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;

    protected $user_sender_id;

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

        //$this->AuthorizesMutation(UserMergeRequest::class, AuthAction::Create, $args);

        $this->user_sender_id= $this->getUserId();

        // Fetch the sender person
        $person = Person::find($args['node_sender_id']);

        // Fetch the receiver user by mobile
        $user_receiver = User::where('mobile', $person->country_code . $person->mobile)
            ->where('status', Status::Active)
            ->first();

        // Fetch the receiver's owner
        $person_receiver_owner = Person::where('creator_id', $user_receiver->id)
            ->where('is_owner', true)
            ->where('status', Status::Active)
            ->first();

        // Prepare data for creating UserMergeRequest
        $UserMergeRequestResult = [
            "creator_id" => $this->user_sender_id,
            "user_sender_id" => $this->user_sender_id,
            "node_sender_id" => $args['node_sender_id'],
            "user_receiver_id" => $user_receiver->id,
            "node_receiver_id" => $person_receiver_owner->id,
            "request_sender_expired_at" => Carbon::now()->addDays(1)->format("Y-m-d H:i:s"),
            "request_status_sender" => RequestStatusSender::Active,
            "status" => MergeStatus::Suspend,

        ];
        $this->checkDuplicate(
            new UserMergeRequest(),
            $UserMergeRequestResult
        );
        // Create the UserMergeRequest
        return UserMergeRequest::create($UserMergeRequestResult);
    }
}