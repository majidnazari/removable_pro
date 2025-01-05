<?php

namespace App\GraphQL\Queries\MiddleField;

use App\Models\MiddleField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetMiddleField
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
    function resolveMiddleField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $MiddleField = MiddleField::where('id', $args['id']);       
        // return $MiddleField->first();

        $MiddleField = $this->getModelByAuthorization(MiddleField::class, $args);
        return $MiddleField->first();
    }
}