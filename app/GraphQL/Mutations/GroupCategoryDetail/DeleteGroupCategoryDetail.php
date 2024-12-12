<?php

namespace App\GraphQL\Mutations\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class DeleteGroupCategoryDetail
{
    use AuthUserTrait;
    use AuthorizesMutation;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveGroupCategoryDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
       $this->userAccessibility(GroupCategoryDetail::class, AuthAction::Delete, $args);

    
        $GroupCategoryDetailResult=GroupCategoryDetail::find($args['id']);
        
        if(!$GroupCategoryDetailResult)
        {
            return Error::createLocatedError("GroupCategoryDetail-DELETE-RECORD_NOT_FOUND");
        }

        $GroupCategoryDetailResult->editor_id=  $this->userId;
        $GroupCategoryDetailResult->save();

        $GroupCategoryDetailResult_filled= $GroupCategoryDetailResult->delete();  
        return $GroupCategoryDetailResult;

        
    }
}