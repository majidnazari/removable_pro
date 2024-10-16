<?php

namespace App\GraphQL\Queries\PersonSpouse;

use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetPersonSpouses
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolvePersonSpouse($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $PersonSpouses = PersonSpouse::where('deleted_at', null);
        return $PersonSpouses;
    }
}