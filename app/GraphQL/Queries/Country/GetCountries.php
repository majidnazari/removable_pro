<?php

namespace App\GraphQL\Queries\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetCountries
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveCountry($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $countries = Country::where('deleted_at', null);
        return $countries;
    }
}