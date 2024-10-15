<?php

namespace App\GraphQL\Mutations\ClanMember;

use App\Models\ClanMember;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateClanMember
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

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $ClanMemberResult=[
            "creator_id" => 1,
            "clan_id" => $args['clan_id'],
            "node_code" => $args['node_code'],
        ];
        $is_exist= ClanMember::where('node_code',$args['node_code'])
        ->where('clan_id',$args['clan_id'])
        ->first();
        if($is_exist)
         {
                 return Error::createLocatedError("ClanMember-CREATE-RECORD_IS_EXIST");
         }
        $ClanMemberResult_result=ClanMember::create($ClanMemberResult);
        return $ClanMemberResult_result;
    }
}