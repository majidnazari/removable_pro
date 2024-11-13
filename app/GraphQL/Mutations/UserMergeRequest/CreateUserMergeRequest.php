<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;

use Log;

final class CreateUserMergeRequest
{
   
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $UserMergeRequestResult=[
            "status" => $args[' '] ?? Status::Active,
            "title" => $args['title'],
            "description" => $args['description']   ?? ""          
        ];
        $is_exist= UserMergeRequest::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("UserMergeRequest-CREATE-RECORD_IS_EXIST");
         }
        $UserMergeRequestResult_result=UserMergeRequest::create($UserMergeRequestResult);
        return $UserMergeRequestResult_result;
    }
}