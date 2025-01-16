<?php

namespace App\GraphQL\Queries\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\CheckUserInGroupTrait;

final class GetFavorites
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    use CheckUserInGroupTrait;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveFavorite($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $Favorites = Favorite::where('deleted_at', null);
        // return $Favorites;

        $query = $this->getModelByAuthorization(Favorite::class, $args, true,true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}