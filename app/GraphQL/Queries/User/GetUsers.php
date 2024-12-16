<?php

namespace App\GraphQL\Queries\User;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\GraphQL\Enums\Role;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;

final class GetUsers
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver 
    }
    function resolveUser($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->user = $this->getUser();

        // //Log::info("the user type  " . $this->user->isAdmin() . " and the user role is:" .$this->user->role  .  "and the enum role is:" . Role::Admin->value );
        // //$user_id=$this->getUserId();
       
        // //$Users = ( $this->user->role === Role::Admin->value) ? User::where('deleted_at', null) : User::where('id', $this->user->id )->where('deleted_at', null) ;
        // $Users = $this->user->isAdmin() || $this->user->isSupporter()
        // ? 
        // User::where('deleted_at', null) 
        // : 
        // User::where('id', $this->user->id )->where('deleted_at', null) 
        // ;

        // //log::info("the Scores is:" . json_encode($UserMergeRequests));
        // return $Users;

        $query = $this->getModelByAuthorization(User::class, $args, true);
        // Apply search filters and ordering
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}