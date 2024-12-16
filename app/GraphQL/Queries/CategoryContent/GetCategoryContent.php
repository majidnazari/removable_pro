<?php

namespace App\GraphQL\Queries\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetCategoryContent
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
    function resolveCategoryContent($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $CategoryContent = CategoryContent::where('id', $args['id']);       
        // return $CategoryContent->first();
        $CategoryContent = $this->getModelByAuthorization(CategoryContent::class, $args);
        return $CategoryContent->first();
    }
}