<?php

namespace App\GraphQL\Queries\UserDetail;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetUserDetail
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
    function resolveUserDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $this->getModelByAuthorization(UserDetail::class, $args);
        return $user->first();
    }
}