<?php

namespace App\GraphQL\Mutations\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;

use Log;

final class CreateGroupDetail
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
    public function resolveGroupDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 
        $this->userId = $this->getUserId();
 
        $GroupDetailModel=[
            "creator_id" =>  $this->userId,
            "person_id" =>  $args['person_id'],
            "group_id" =>  $args['group_id'],
            "title" => $args['title'],          
            //"status" => $args['status'] ?? Status::Active            
        ];
        $is_exist= GroupDetail::where($GroupDetailModel)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("GroupDetail-CREATE-RECORD_IS_EXIST");
         }
        $GroupDetailResult=GroupDetail::create($GroupDetailModel);
        return $GroupDetailResult;
    }
}