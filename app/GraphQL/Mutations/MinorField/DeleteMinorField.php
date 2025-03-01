<?php

namespace App\GraphQL\Mutations\MinorField;

use App\Models\MinorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteMinorField
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
    public function resolveMinorField($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();

        try {

            $MinorFieldResult = $this->userAccessibility(MinorField::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($MinorFieldResult, $args, $this->userId);
       
    }
}