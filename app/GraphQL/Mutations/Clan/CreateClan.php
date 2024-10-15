<?php

namespace App\GraphQL\Mutations\Clan;

use App\Models\Clan;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateClan
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

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $ClanResult=[
            "creator_id" => 1,
            "title" => $args['title'],
            "Clan_exact_family_name" => $args['Clan_exact_family_name'],
            "Clan_code" => $args['Clan_code']            
        ];
        $is_exist= Clan::where('title',$args['title'])
        ->where('Clan_code',$args['Clan_code'])
        ->where('Clan_exact_family_name',$args['Clan_exact_family_name'])
        ->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Clan-CREATE-RECORD_IS_EXIST");
         }
        $ClanResult_result=Clan::create($ClanResult);
        return $ClanResult_result;
    }
}