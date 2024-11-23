<?php

namespace App\GraphQL\Mutations\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteClanMember
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
        $ClanMemberResult=ClanMember::find($args['id']);
        
        if(!$ClanMemberResult)
        {
            return Error::createLocatedError("ClanMember-DELETE-RECORD_NOT_FOUND");
        }
        $ClanMemberResult->editor_id= $this->userId;
        $ClanMemberResult->save();

        $ClanMemberResult_filled= $ClanMemberResult->delete();  
        return $ClanMemberResult;

        
    }
}