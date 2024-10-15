<?php

namespace App\GraphQL\Queries\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetFamilyBoard
{
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
        $FamilyBoard = FamilyBoard::where('id', $args['id']);       
        return $FamilyBoard->first();
    }
}