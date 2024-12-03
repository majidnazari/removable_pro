<?php

namespace App\GraphQL\Queries\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetPersonChild
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
    function resolvePersonChild($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $PersonChild = PersonChild::where('id', $args['id']);       
        // return $PersonChild->first();

        $PersonChild = $this->getModelByAuthorization(PersonChild::class, $args);
        return $PersonChild->first();
    }
}