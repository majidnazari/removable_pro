<?php

namespace App\GraphQL\Queries\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetPersonMarriage
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
    function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $PersonMarriage = PersonMarriage::where('id', $args['id']);       
        // return $PersonMarriage->first();

        $user = $this->getModelByAuthorization(PersonMarriage::class, $args);
        return $user->first();
    }
}