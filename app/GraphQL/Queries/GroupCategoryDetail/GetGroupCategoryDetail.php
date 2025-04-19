<?php

namespace App\GraphQL\Queries\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetGroupCategoryDetail
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
    function resolveGroupCategoryDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $GroupCategoryDetail = $this->getModelByAuthorization(GroupCategoryDetail::class, $args);
        return $GroupCategoryDetail->first();
    }
}