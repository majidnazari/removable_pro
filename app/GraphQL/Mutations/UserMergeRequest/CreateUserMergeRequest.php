<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;

use Exception;
use Log;

final class CreateUserMergeRequest
{
    use AuthUserTrait;
    use AuthorizesMutation;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();

    }
}