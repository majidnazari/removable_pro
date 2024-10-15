<?php

namespace App\GraphQL\Queries\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetFamilyEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $FamilyEvent = FamilyEvent::where('id', $args['id']);       
        return $FamilyEvent->first();
    }
}