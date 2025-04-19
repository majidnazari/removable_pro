<?php

namespace App\GraphQL\Mutations\Group;

use App\Models\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\DuplicateCheckTrait;


use Log;

final class CreateGroup
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    use DuplicateCheckTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveGroup($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $GroupModel = [
            "creator_id" => $this->userId,
            "title" => $args['title'],
            "status" => $args['status'] ?? Status::Active
        ];


        $this->checkDuplicate(new Group(), $GroupModel);
        $GroupResult = Group::create($GroupModel);
        return $GroupResult;
    }
}