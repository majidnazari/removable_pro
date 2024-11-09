<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateNaslanRelationship
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user_id=auth()->guard('api')->user()->id;
        $NaslanRelationResult=[
            "status" => $args['status'] ?? "None",           
            "title" => $args['title'],
        ];
        $is_exist= NaslanRelationship::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("NaslanRelation-CREATE-RECORD_IS_EXIST");
         }
        $NaslanRelationResult_result=NaslanRelationship::create($NaslanRelationResult);
        return $NaslanRelationResult_result;
    }
}