<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;
use App\Exceptions\CustomValidationException;

use Log;

final class DeletePersonDetails
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
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();


        try {

            $PersonDetailResult = $this->userAccessibility(PersonDetail::class, AuthAction::Delete, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($PersonDetailResult, $args, $this->userId);
    }
}