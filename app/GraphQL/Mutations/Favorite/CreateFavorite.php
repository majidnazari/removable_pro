<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateFavorite
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

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

        $this->userId = $this->getUserId();

        $FavoriteModel=[
            "creator_id" =>  $this->userId,
            "person_id" => $args['person_id'],

            "image" => $args['image'],
            "title" => $args['title'],
            "description" => $args['description'],
            "star" => $args['star'] ?? Star::Five   ,
            "status" => $args['status']  ?? status::Active       
        ];
        // $is_exist= Favorite::where($FavoriteModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("Favorite-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new Favorite(), $FavoriteModel);
        $FavoriteResult=Favorite::create($FavoriteModel);
        return $FavoriteResult;
    }
}