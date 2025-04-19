<?php

namespace App\GraphQL\Queries\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetGroupDetail
{
    use AuthUserTrait;
    use AuthorizesUser;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveGroupDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $GroupDetail = $this->getModelByAuthorization(GroupDetail::class, $args);
        return $GroupDetail->first();
    }
}