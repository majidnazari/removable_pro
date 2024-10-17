<?php

namespace App\GraphQL\Queries\NaslanSubscription;

use App\Models\Subscription;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;

final class GetNaslanSubscriptions
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
        $NaslanSubscriptions = Subscription::where('deleted_at', null);

        //log::info("the NaslanSubscriptions is:" . json_encode($NaslanSubscriptions));
        return $NaslanSubscriptions;
    }
}