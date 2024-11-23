<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;
use Illuminate\Support\Facades\Auth;
use Exception;

final class CreateProvince
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }
        $ProvinceResult=[
            "country_id" => $args['country_id'],
            "title" => $args['title'],
            "code" => $args['code']            
        ];
        $is_exist= Province::where('title',$args['title'])->where('code',$args['code'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Province-CREATE-RECORD_IS_EXIST");
         }
        $ProvinceResult_result=Province::create($ProvinceResult);
        return $ProvinceResult_result;
    }
}