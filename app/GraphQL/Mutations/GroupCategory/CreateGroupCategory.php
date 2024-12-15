<?php

namespace App\GraphQL\Mutations\GroupCategory;

use App\Models\GroupCategory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreateGroupCategory
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

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
 
        $GroupCategoryModel=[
            "creator_id" =>  $this->userId,
            "title" => $args['title'],          
            "status" => $args['status'] ?? Status::Active            
        ];
        // $is_exist= GroupCategory::where($GroupCategoryModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("GroupCategory-CREATE-RECORD_IS_EXIST");
        //  }
         $this->checkDuplicate(new GroupCategory(),  $GroupCategoryModel);
        $GroupCategoryResult=GroupCategory::create($GroupCategoryModel);
        return $GroupCategoryResult;
    }
}