<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Favorites\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateFavorite
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

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $FavoriteModel=[
            "creator_id" => 1,
            "person_id" => $args['person_id'],

            "image" => $args['image'],
            "title" => $args['title'],
            "description" => $args['description'],
            "star" => $args['star'],
            "status" => $args['status']            
        ];
        $is_exist= Favorite::where($FavoriteModel)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Favorite-CREATE-RECORD_IS_EXIST");
         }
        $FavoriteResult=Favorite::create($FavoriteModel);
        return $FavoriteResult;
    }
}