<?php

namespace App\GraphQL\Queries\NaslanSubscription;

use App\Models\Subscription;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetNaslanSubscription
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveNaslanSubscription($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $NaslanSubscription = Subscription::where('id', $args['id']);       
        return $NaslanSubscription->first();
    }
}