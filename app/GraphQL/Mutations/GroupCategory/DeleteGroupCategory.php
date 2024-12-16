<?php

namespace App\GraphQL\Mutations\GroupCategory;

use App\Models\GroupCategory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class DeleteGroupCategory
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
    public function resolveGroupCategory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
        $this->userAccessibility(GroupCategory::class, AuthAction::Delete, $args);

    
        $GroupCategoryResult=GroupCategory::find($args['id']);
        
        if(!$GroupCategoryResult)
        {
            return Error::createLocatedError("GroupCategory-DELETE-RECORD_NOT_FOUND");
        }

        $GroupCategoryResult->editor_id=  $this->userId;
        $GroupCategoryResult->save();

        $GroupCategoryResult_filled= $GroupCategoryResult->delete();  
        return $GroupCategoryResult;

        
    }
}