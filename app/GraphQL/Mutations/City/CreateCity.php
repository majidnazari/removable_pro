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
use App\Traits\AuthUserTrait;

use Log;

final class CreateCity
{
    use AuthUserTrait;
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

        $this->userId = $this->getUserId();


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