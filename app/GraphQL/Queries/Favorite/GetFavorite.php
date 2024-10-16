<?php

namespace App\GraphQL\Queries\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class GetFavorite
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
        $Favorite = Favorite::where('id', $args['id']);       
        return $Favorite->first();
    }
}