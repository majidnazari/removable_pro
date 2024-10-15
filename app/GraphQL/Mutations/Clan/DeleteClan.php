<?php

namespace App\GraphQL\Mutations\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteClan
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
       // $user_id=auth()->guard('api')->user()->id;        
        $ClanResult=Clan::find($args['id']);
        
        if(!$ClanResult)
        {
            return Error::createLocatedError("Clan-DELETE-RECORD_NOT_FOUND");
        }
        $ClanResult_filled= $ClanResult->delete();  
        return $ClanResult;

        
    }
}