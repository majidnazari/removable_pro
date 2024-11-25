<?php

namespace App\GraphQL\Queries\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;


final class GetAddress
{
    use AuthUserTrait;
    protected $userId;
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
        $this->userId = $this->getUserId();
        $Address = Address::where('id', $args['id']);       
        return $Address->first();
    }
}