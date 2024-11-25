<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;



final class UpdateCategoryContent
{
    use AuthUserTrait;
    protected $userId;

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
        $this->userId = $this->getUserId();

        //args["user_id_creator"]=$user_id;
        $CategoryContentResult=CategoryContent::find($args['id']);
        
        if(!$CategoryContentResult)
        {
            return Error::createLocatedError("CategoryContent-UPDATE-RECORD_NOT_FOUND");
        }
        $CategoryContentResult_filled= $CategoryContentResult->fill($args);
        $CategoryContentResult->save();       
       
        return $CategoryContentResult;

        
    }
}