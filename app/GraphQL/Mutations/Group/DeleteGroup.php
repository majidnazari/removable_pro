<?php

namespace App\GraphQL\Mutations\Group;

use App\Models\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;
use Exception;



final class DeleteGroup
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
    public function resolveGroup($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        try {

            $GroupResult = $this->userAccessibility(Group::class, AuthAction::Delete, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($GroupResult, $args, $this->userId);

    }
}