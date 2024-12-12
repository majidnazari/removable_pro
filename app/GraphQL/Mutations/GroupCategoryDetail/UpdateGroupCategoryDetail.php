<?php

namespace App\GraphQL\Mutations\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;



final class UpdateGroupCategoryDetail
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
       $this->userAccessibility(GroupCategoryDetail::class, AuthAction::Update, $args);


        //args["user_id_creator"]=$user_id;
        $GroupCategoryDetailResult=GroupCategoryDetail::find($args['id']);
        
        if(!$GroupCategoryDetailResult)
        {
            return Error::createLocatedError("GroupCategoryDetail-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;
        $GroupCategoryDetailResult_filled= $GroupCategoryDetailResult->fill($args);
        $GroupCategoryDetailResult->save();       
       
        return $GroupCategoryDetailResult;

        
    }
}