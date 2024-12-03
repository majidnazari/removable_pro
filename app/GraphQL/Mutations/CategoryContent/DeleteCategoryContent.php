<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class DeleteCategoryContent
{
    use AuthUserTrait;
    use checkMutationAuthorization;
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
        $this->checkMutationAuthorization(CategoryContent::class, AuthAction::Delete, $args);

       
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