<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Log;
use Exception;
use App\Exceptions\CustomValidationException;




final class DeleteAddress
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    protected $userId;
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveAddress($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        $AddressResult = $this->userAccessibility(Address::class, AuthAction::Delete, $args);
        return $this->updateAndDeleteModel($AddressResult, $args, $this->userId);

    }
}