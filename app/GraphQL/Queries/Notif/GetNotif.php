<?php

namespace App\GraphQL\Queries\Notif;

use App\Models\Notif;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetNotif
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
    function resolveNotif($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $this->getModelByAuthorization(Notif::class, $args);
        return $user->first();
    }
}