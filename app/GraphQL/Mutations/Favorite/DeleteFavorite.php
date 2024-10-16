<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteFavorite
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFavorite($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $FavoriteResult=Favorite::find($args['id']);
        
        if(!$FavoriteResult)
        {
            return Error::createLocatedError("Favorite-DELETE-RECORD_NOT_FOUND");
        }
        $FavoriteResult_filled= $FavoriteResult->delete();  
        return $FavoriteResult;

        
    }
}