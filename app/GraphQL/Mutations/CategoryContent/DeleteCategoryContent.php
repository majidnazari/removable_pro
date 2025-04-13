<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;

final class DeleteCategoryContent
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCategoryContent($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
        try {

            $CategoryContentResult = $this->userAccessibility(CategoryContent::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new CustomValidationException("CategoryContent-DELETE-FAILED", "CategoryContent-DELETE-FAILED", 400);

            //throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($CategoryContentResult, $args, $this->userId);
    //    $this->userAccessibility(CategoryContent::class, AuthAction::Delete, $args);

       
    //     $CategoryContentResult=CategoryContent::find($args['id']);
        
    //     if(!$CategoryContentResult)
    //     {
    //         return Error::createLocatedError("CategoryContent-DELETE-RECORD_NOT_FOUND");
    //     }
        
    //     $CategoryContentResult->editor_id=$this->userId ;
    //     $CategoryContentResult->save();

    //     $CategoryContentResult_filled= $CategoryContentResult->delete();  
    //     return $CategoryContentResult;

        
    }
}