<?php

namespace App\GraphQL\Mutations\Favorite;

use App\Models\Favorite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteFavorite
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
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

        try {

            $FavoriteResult = $this->userAccessibility(Favorite::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($FavoriteResult, $args, $this->userId);
       
        
    }
}