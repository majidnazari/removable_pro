<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteCategoryContent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCategoryContent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $CategoryContentResult=CategoryContent::find($args['id']);
        
        if(!$CategoryContentResult)
        {
            return Error::createLocatedError("CategoryContent-DELETE-RECORD_NOT_FOUND");
        }
        $CategoryContentResult_filled= $CategoryContentResult->delete();  
        return $CategoryContentResult;

        
    }
}