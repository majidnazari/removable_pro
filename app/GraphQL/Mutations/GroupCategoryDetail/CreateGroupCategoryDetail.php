<?php

namespace App\GraphQL\Mutations\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateGroupCategoryDetail
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
    public function resolveGroupCategoryDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $GroupCategoryDetailModel = [
            "creator_id" => $this->userId,
            "group_category_id" => $args['group_category_id'],
            "group_id" => $args['group_id'],
            "status" => $args['status'] ?? Status::Active
        ];

        $this->checkDuplicate(new GroupCategoryDetail(), $GroupCategoryDetailModel);
        $GroupCategoryDetailResult = GroupCategoryDetail::create($GroupCategoryDetailModel);
        return $GroupCategoryDetailResult;
    }
}