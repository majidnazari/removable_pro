<?php

namespace App\GraphQL\Mutations\GroupView;

use App\Models\GroupView;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteGroupView
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveGroupView($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;        
        $GroupViewResult=GroupView::find($args['id']);
        
        if(!$GroupViewResult)
        {
            return Error::createLocatedError("GroupView-DELETE-RECORD_NOT_FOUND");
        }

        $GroupViewResult->editor_id= $user_id;
        $GroupViewResult->save();

        $GroupViewResult_filled= $GroupViewResult->delete();  
        return $GroupViewResult;

        
    }
}