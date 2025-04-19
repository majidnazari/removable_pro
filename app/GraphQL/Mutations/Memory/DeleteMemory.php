<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use App\Traits\HandlesModelUpdateAndDelete;
use Exception;
use App\Exceptions\CustomValidationException;


final class DeleteMemory
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
    public function resolveMemory($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        try {

            $GroupDetailResult = $this->userAccessibility(Memory::class, AuthAction::Delete, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        }  catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($GroupDetailResult, $args, $this->userId);

       

    }
}