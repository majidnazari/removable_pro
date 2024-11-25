<?php

namespace App\GraphQL\Mutations\GroupView;

use App\Models\GroupView;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;



final class UpdateGroupView
{
    use AuthUserTrait;
    protected $userId;

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
        $this->userId = $this->getUserId();

        //args["user_id_creator"]=$user_id;
        $GroupViewResult=GroupView::find($args['id']);
        
        if(!$GroupViewResult)
        {
            return Error::createLocatedError("GroupView-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;
        $GroupViewResult_filled= $GroupViewResult->fill($args);
        $GroupViewResult->save();       
       
        return $GroupViewResult;

        
    }
}