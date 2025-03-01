<?php

namespace App\GraphQL\Mutations\GroupCategory;

use App\Models\GroupCategory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteGroupCategory
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
    public function resolveGroupCategory($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();

        try {

            $GroupCategoryResult = $this->userAccessibility(GroupCategory::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($GroupCategoryResult, $args, $this->userId);
        // $this->userAccessibility(GroupCategory::class, AuthAction::Delete, $args);

    
        // $GroupCategoryResult=GroupCategory::find($args['id']);
        
        // if(!$GroupCategoryResult)
        // {
        //     return Error::createLocatedError("GroupCategory-DELETE-RECORD_NOT_FOUND");
        // }

        // try {

        //     $GroupCategoryResult = $this->userAccessibility(GroupCategory::class, AuthAction::Delete, $args);

        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }

        // $GroupCategoryResult->update([
        //     'editor_id' => $this->userId,
        // ]);
        
        // $GroupCategoryResult->delete();
        
        // return $GroupCategoryResult;
        
    }
}