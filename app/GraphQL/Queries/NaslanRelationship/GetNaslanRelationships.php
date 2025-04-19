<?php

namespace App\GraphQL\Queries\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;


use Log;

final class GetNaslanRelationships
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
    function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $NaslanRelationships = NaslanRelationship::where('deleted_at', null);
        return $NaslanRelationships;
    }
}