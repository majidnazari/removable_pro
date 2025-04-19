<?php

namespace App\GraphQL\Queries\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\MergeRequestTrait;
use Log;

final class GetMemory
{
    use AuthUserTrait;
    use AuthorizesUser;
    use MergeRequestTrait;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveMemory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $Memory = $this->getModelByAuthorization(Memory::class, $args);
        return $Memory->first();
    }
}