<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

final class CreateCity
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
    public function resolveCity($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        $CityResult=[
            "province_id" => $args['province_id'],
            "title" => $args['title'],
            "code" => $args['code']            
        ];
        $is_exist= City::where('title',$args['title'])->where('code',$args['code'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("City-CREATE-RECORD_IS_EXIST");
         }
        $CityResult_result=City::create($CityResult);
        return $CityResult_result;
    }
}