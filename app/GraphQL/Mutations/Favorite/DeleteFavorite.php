<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class DeleteFavorite
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
       $this->userAccessibility(Favorite::class, AuthAction::Delete, $args);

        
        $FavoriteResult=Favorite::find($args['id']);
        
        if(!$FavoriteResult)
        {
            return Error::createLocatedError("Favorite-DELETE-RECORD_NOT_FOUND");
        }

        $FavoriteResult->editor_id= $this->userId;
        $FavoriteResult->save();

        $FavoriteResult_filled= $FavoriteResult->delete();  
        return $FavoriteResult;

        
    }
}