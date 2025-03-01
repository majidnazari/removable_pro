<?php

namespace App\GraphQL\Mutations\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;

use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteGroupCategoryDetail
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
    public function resolveGroupCategoryDetail($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        try {

            $GroupCategoryDetailResult = $this->userAccessibility(GroupCategoryDetail::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($GroupCategoryDetailResult, $args, $this->userId);
        // $this->userAccessibility(GroupCategoryDetail::class, AuthAction::Delete, $args);


        // $GroupCategoryDetailResult=GroupCategoryDetail::find($args['id']);

        // if(!$GroupCategoryDetailResult)
        // {
        //     return Error::createLocatedError("GroupCategoryDetail-DELETE-RECORD_NOT_FOUND");
        // }

        // try {

        //     $GroupCategoryDetailResult = $this->userAccessibility(GroupCategoryDetail::class, AuthAction::Delete, $args);

        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }

        // $GroupCategoryDetailResult->editor_id=  $this->userId;
        // $GroupCategoryDetailResult->save();

        // $GroupCategoryDetailResult_filled= $GroupCategoryDetailResult->delete();  
        // return $GroupCategoryDetailResult;


    }
}