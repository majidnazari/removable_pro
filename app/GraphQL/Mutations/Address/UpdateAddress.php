<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;

use Log;


final class UpdateAddress
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
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

        $AddressResult = $this->userAccessibility(Address::class, AuthAction::Update, $args);
        $this->checkDuplicate(
            new Address(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            $args['id']
        );

        return $this->updateModel($AddressResult, $args, $this->userId);

    }
}