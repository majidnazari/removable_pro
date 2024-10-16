<?php

namespace App\GraphQL\Queries\PersonSpouse;

use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetPersonSpouse
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
        $PersonSpouse = PersonSpouse::where('id', $args['id']);       
        return $PersonSpouse->first();
    }
}