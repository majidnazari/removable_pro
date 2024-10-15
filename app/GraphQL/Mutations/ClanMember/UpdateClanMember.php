<?php

namespace App\GraphQL\Mutations\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateClanMember
{
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
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $ClanMemberResult=ClanMember::find($args['id']);
        
        if(!$ClanMemberResult)
        {
            return Error::createLocatedError("ClanMember-UPDATE-RECORD_NOT_FOUND");
        }
        $ClanMemberResult_filled= $ClanMemberResult->fill($args);
        $ClanMemberResult->save();       
       
        return $ClanMemberResult;

        
    }
}