<?php

namespace App\GraphQL\Mutations\Group;

use App\Models\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\AuthAction;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;


final class UpdateGroup
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;


    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveGroup($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();
        $GroupResult = Group::find($args['id']);
        $this->userAccessibility(Group::class, AuthAction::Update, $args);

        if (!$GroupResult) {
            return Error::createLocatedError("Group-UPDATE-RECORD_NOT_FOUND");
        }
        $this->checkDuplicate(
            new Group(),
            $args,
            ['id','editor_id','created_at', 'updated_at'],
            $args['id']
        );
        $args['editor_id'] = $this->userId;
        $GroupResult_filled = $GroupResult->fill($args);
        $GroupResult->save();

        return $GroupResult;


    }
}