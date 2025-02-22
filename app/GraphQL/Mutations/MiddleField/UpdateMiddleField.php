<?php

namespace App\GraphQL\Mutations\MiddleField;

use App\Models\MiddleField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class UpdateMiddleField
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveMiddleField($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        try {

            $MiddleFieldResult = $this->userAccessibility(MiddleField::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new MiddleField(),
            $args,
            ['id'],
        );

        return $this->updateModel($MiddleFieldResult, $args, $this->userId);


    }
}