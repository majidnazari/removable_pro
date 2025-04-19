<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreatePersonChild
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;


    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $PersonChildModel = [
            "creator_id" => $this->userId,
            "editor_id" => $args['editor_id'] ?? null,
            "person_marriage_id" => $args['person_marriage_id'],
            "child_id" => $args['child_id'],
            "child_kind" => $args['child_kind'] ?? ChildKind::DirectChild, // Default to 'Direct_child' if not provided
            "child_status" => $args['child_status'] ?? ChildStatus::WithFamily, // Default to 'With_family' if not provided
            "status" => $args['status'] ?? status::Active // Default to 'Active' if not provided
        ];



        $this->checkDuplicate(new PersonChild(), $PersonChildModel);
        $PersonChildResult = PersonChild::create($PersonChildModel);
        return $PersonChildResult;
    }
}