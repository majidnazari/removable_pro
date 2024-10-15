<?php

namespace App\GraphQL\Queries\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveEvent($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Event = Event::where('id', $args['id']);       
        return $Event->first();
    }
}