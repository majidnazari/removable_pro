<?php

namespace App\GraphQL\Queries\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetFamilyBoards
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
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
        // $this->userId = $this->getUserId();

        // $FamilyBoards = FamilyBoard::where('deleted_at', null);
        // return $FamilyBoards;

        $query = $this->getModelByAuthorization(FamilyBoard::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}