<?php

namespace App\GraphQL\Queries\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;


final class GetCountries
{
    use AuthUserTrait;
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
        $this->userId = $this->getUserId();

        $countries = Country::where('deleted_at', null);
        return $countries;
    }
}