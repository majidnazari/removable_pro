<?php

namespace App\GraphQL\Queries\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetAddresses
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveAddress($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Addresss = Address::where('deleted_at', null)->with("City");
        return $Addresss;
    }
}