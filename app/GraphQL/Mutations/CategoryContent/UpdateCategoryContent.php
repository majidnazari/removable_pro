<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;



final class UpdateCategoryContent
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
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

            $CategoryContentResult = $this->userAccessibility(CategoryContent::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new CustomValidationException("CategoryContent-UPDATE-FAILED", "CategoryContent-UPDATE-FAILED", 400);

            //throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new CategoryContent(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($CategoryContentResult, $args, $this->userId);
    //    $this->userAccessibility(CategoryContent::class, AuthAction::Delete, $args);


    //     //args["user_id_creator"]=$user_id;
    //     $CategoryContentResult=CategoryContent::find($args['id']);
        
    //     if(!$CategoryContentResult)
    //     {
    //         return Error::createLocatedError("CategoryContent-UPDATE-RECORD_NOT_FOUND");
    //     }
    //     $CategoryContentResult_filled= $CategoryContentResult->fill($args);
    //     $CategoryContentResult->save();       
       
    //     return $CategoryContentResult;

        
    }
}