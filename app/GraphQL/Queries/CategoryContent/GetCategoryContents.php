<?php

namespace App\GraphQL\Queries\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetCategoryContents
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
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
        // $this->userId = $this->getUserId();

        // $CategoryContents = CategoryContent::where('deleted_at', null);
        // return $CategoryContents;
        $query = $this->getModelByAuthorization(CategoryContent::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}