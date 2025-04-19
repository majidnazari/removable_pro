<?php

namespace App\GraphQL\Queries\MinorField;

use App\Models\MinorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetMinorField
{
    use AuthUserTrait;
    use AuthorizesUser;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveMinorField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $MinorField = $this->getModelByAuthorization(MinorField::class, $args);
        return $MinorField->first();
    }
}