<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteCategoryContent
{
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
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;       
        $CategoryContentResult=CategoryContent::find($args['id']);
        
        if(!$CategoryContentResult)
        {
            return Error::createLocatedError("CategoryContent-DELETE-RECORD_NOT_FOUND");
        }
        
        $CategoryContentResult->editor_id=$this->userId ;
        $CategoryContentResult->save();

        $CategoryContentResult_filled= $CategoryContentResult->delete();  
        return $CategoryContentResult;

        
    }
}