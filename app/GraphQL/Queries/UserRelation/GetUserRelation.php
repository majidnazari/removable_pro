<?php

namespace App\GraphQL\Queries\UserRelation;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetUserRelation
{
    use  AuthUserTrait;
    use AuthorizesUser;
  
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
        

        // $User = 
        // $this->user->isAdmin() || $this->user->isSupporter() 
        // ?
        //  User::where('id', $args['id'])
        // : 
        //  User::where('id', $args['id'])->where('country_code', $this->user->country_code)->where('mobile', $this->user->mobile);  

        // return $User->first();

        $user = $this->getModelByAuthorization(User::class, $args);
        return $user->first();
    }
}