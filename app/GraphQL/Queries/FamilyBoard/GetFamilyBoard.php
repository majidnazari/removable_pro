<?php

namespace App\GraphQL\Queries\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetFamilyBoard
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
    function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $FamilyBoard = $this->getModelByAuthorization(FamilyBoard::class, $args);
        return $FamilyBoard->first();
    }
}