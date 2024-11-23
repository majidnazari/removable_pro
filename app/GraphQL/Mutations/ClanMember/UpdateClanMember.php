<?php

namespace App\GraphQL\Mutations\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateClanMember
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
    public function resolveClanMember($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        //args["user_id_creator"]=$user_id;
        $ClanMemberResult=ClanMember::find($args['id']);
        
        if(!$ClanMemberResult)
        {
            return Error::createLocatedError("ClanMember-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;
        $ClanMemberResult_filled= $ClanMemberResult->fill($args);
        $ClanMemberResult->save();       
       
        return $ClanMemberResult;

        
    }
}