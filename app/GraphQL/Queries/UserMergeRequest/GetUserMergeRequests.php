<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetUserMergeRequests
{
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
        $UserMergeRequests = UserMergeRequest::where('deleted_at', null);

        //log::info("the Scores is:" . json_encode($UserMergeRequests));
        return $UserMergeRequests;
    }
}