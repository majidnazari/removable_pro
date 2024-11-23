<?php

namespace App\GraphQL\Mutations\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;


final class UpdateClan
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
    public function resolveClan($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        //args["user_id_creator"]=$user_id;
        $ClanResult=Clan::find($args['id']);
        
        if(!$ClanResult)
        {
            return Error::createLocatedError("Clan-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId ;
        $ClanResult_filled= $ClanResult->fill($args);
        $ClanResult->save();       
       
        return $ClanResult;

        
    }
}