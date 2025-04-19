<?php

namespace App\GraphQL\Mutations\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreateGroupDetail
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
    public function resolveGroupDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $GroupDetailModel = [
            "creator_id" => $this->userId,
            // "person_id" =>  $args['person_id'],
            "user_id" => $args['user_id'],
            "group_id" => $args['group_id'],
            // "title" => $args['title'],          
            //"status" => $args['status'] ?? Status::Active            
        ];

        $this->checkDuplicate(new GroupDetail(), $GroupDetailModel);
        $GroupDetailResult = GroupDetail::create($GroupDetailModel);
        return $GroupDetailResult;
    }
}