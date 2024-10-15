<?php

namespace App\GraphQL\Queries\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetCategoryContent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveCategoryContent($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $CategoryContent = CategoryContent::where('id', $args['id']);       
        return $CategoryContent->first();
    }
}