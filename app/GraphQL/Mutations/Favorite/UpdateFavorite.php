<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;


final class UpdateFavorite
{
    protected $userId;

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
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        //args["user_id_creator"]=$user_id;
        $FavoriteResult=Favorite::find($args['id']);
        
        if(!$FavoriteResult)
        {
            return Error::createLocatedError("Favorite-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $FavoriteResult_filled= $FavoriteResult->fill($args);
        $FavoriteResult->save();       
       
        return $FavoriteResult;

        
    }
}