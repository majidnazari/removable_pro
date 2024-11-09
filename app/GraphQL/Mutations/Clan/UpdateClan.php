<?php

namespace App\GraphQL\Mutations\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateClan
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveClan($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $ClanResult=Clan::find($args['id']);
        
        if(!$ClanResult)
        {
            return Error::createLocatedError("Clan-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $ClanResult_filled= $ClanResult->fill($args);
        $ClanResult->save();       
       
        return $ClanResult;

        
    }
}