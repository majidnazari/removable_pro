<?php

namespace App\GraphQL\Mutations\GroupView;

use App\Models\GroupView;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;

use Log;

final class CreateGroupView
{
    use AuthUserTrait;
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
 
        $GroupViewModel=[
           
            "title" => $args['title'],          
            "status" => $args['status'] ?? Status::Active            
        ];
        $is_exist= GroupView::where($GroupViewModel)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("GroupView-CREATE-RECORD_IS_EXIST");
         }
        $GroupViewResult=GroupView::create($GroupViewModel);
        return $GroupViewResult;
    }
}