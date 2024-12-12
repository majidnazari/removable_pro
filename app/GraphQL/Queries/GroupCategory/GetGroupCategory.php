<?php

namespace App\GraphQL\Queries\GroupCategory;

use App\Models\GroupCategory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetGroupCategory
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
    function resolveGroupCategory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $GroupCategory = GroupCategory::where('id', $args['id']);       
        // return $GroupCategory->first();

        $GroupCategory = $this->getModelByAuthorization(GroupCategory::class, $args);
        return $GroupCategory->first();
    }
}