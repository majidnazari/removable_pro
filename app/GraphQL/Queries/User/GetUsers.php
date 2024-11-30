<?php

namespace App\GraphQL\Queries\User;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use Log;

final class GetUsers
{
    use AuthUserTrait;
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
        $this->userId = $this->getUserId();

        //$user_id=$this->getUserId();
       
        $Users = ( $this->userId ==1) ? User::where('deleted_at', null) : User::where('id', $this->userId )->where('deleted_at', null) ;

        //log::info("the Scores is:" . json_encode($UserMergeRequests));
        return $Users;
    }
}