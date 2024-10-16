<?php

namespace App\GraphQL\Queries\GroupView;

use App\Models\GroupView;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetGroupViews
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveGroupView($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $GroupViews = GroupView::where('deleted_at', null);
        return $GroupViews;
    }
}