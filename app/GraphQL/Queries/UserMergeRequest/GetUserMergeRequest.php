<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetUserMergeRequest
{
    use AuthUserTrait;
    use AuthorizesUser;
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
        $userMergeRequest = $this->getModelByAuthorization(UserMergeRequest::class, $args);
        return $userMergeRequest->first();
    }
}