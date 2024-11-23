<?php

namespace App\GraphQL\Mutations\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

final class CreateScore
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
    public function resolveScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }
        $ScoreResult=[
            "status" => $args['status'] ?? Status::Active,
            "title" => $args['title'],
        ];
        $is_exist= Score::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Score-CREATE-RECORD_IS_EXIST");
         }
        $ScoreResult_result=Score::create($ScoreResult);
        return $ScoreResult_result;
    }
}