<?php

namespace App\GraphQL\Mutations\GroupCategory;

use App\Models\GroupCategory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;



final class UpdateGroupCategory
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
       $this->userAccessibility(GroupCategory::class, AuthAction::Update, $args);


        //args["user_id_creator"]=$user_id;
        $GroupCategoryResult=GroupCategory::find($args['id']);
        
        if(!$GroupCategoryResult)
        {
            return Error::createLocatedError("GroupCategory-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;
        $GroupCategoryResult_filled= $GroupCategoryResult->fill($args);
        $GroupCategoryResult->save();       
       
        return $GroupCategoryResult;

        
    }
}