<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetUserMergeRequest
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
        $UserMergeRequest = UserMergeRequest::where('id', $args['id']);       
        return $UserMergeRequest->first();
    }
}