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
use App\Traits\FindOwnerTrait;
use Log;

final class CreateFavorite
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use FindOwnerTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFavorite($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        $FavoriteModel = [
            "creator_id" => $this->userId,
            "person_id" =>  $this->findOwner()->id,

            "image" => $args['image'] ?? null,
            "title" => $args['title'],
            "description" => $args['description'] ?? null,
            "priority" => $args['priority'] ?? 0,
            "status" => $args['status'] ?? status::Active
        ];
        // $is_exist= Favorite::where($FavoriteModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("Favorite-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new Favorite(), ["title" =>$args['title'] , "person_id" => $args['person_id'] ?? $this->findOwner()->id]);
        $FavoriteResult = Favorite::create($FavoriteModel);
        return $FavoriteResult;
    }
}