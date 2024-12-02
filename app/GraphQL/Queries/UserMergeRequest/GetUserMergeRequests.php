<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use Log;

final class GetUserMergeRequests
{
    use AuthUserTrait;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

       // $user_id = $this->getUserId();

        $UserMergeRequests = UserMergeRequest::where('user_sender_id', $this->userId)
        ->orWhere('user_receiver_id',$this->userId)
        ->where('deleted_at', null);

        

        //log::info("the Scores is:" . json_encode($UserMergeRequests));
        return $UserMergeRequests;
    }
}