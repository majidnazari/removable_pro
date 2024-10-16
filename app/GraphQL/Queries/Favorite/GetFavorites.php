<?php

namespace App\GraphQL\Queries\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetFavorites
{
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
        $Favorites = Favorite::where('deleted_at', null);
        return $Favorites;
    }
}