<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class UpdateFavorite
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
        // $this->userAccessibility(Favorite::class, AuthAction::Delete, $args);


        // //args["user_id_creator"]=$user_id;
        // $FavoriteResult=Favorite::find($args['id']);

        // if(!$FavoriteResult)
        // {
        //     return Error::createLocatedError("Favorite-UPDATE-RECORD_NOT_FOUND");
        // }
        try {

            $FavoriteResult = $this->userAccessibility(Favorite::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->checkDuplicate(
            new Favorite(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            $args['id']
        );
        $args['editor_id'] = $this->userId;
        $FavoriteResult_filled = $FavoriteResult->fill($args);
        $FavoriteResult->save();

        return $FavoriteResult;


    }
}