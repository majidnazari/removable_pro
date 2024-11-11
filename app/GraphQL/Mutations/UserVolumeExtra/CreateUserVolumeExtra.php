<?php

namespace App\GraphQL\Mutations\UserVolumeExtra;

use App\Models\UserVolumeExtra;
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

final class CreateUserVolumeExtra
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserVolumeExtra($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        


        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $UserVolumeExtraModel=[

            "status" => $args['status'] ?? Status::None,
            "start_date" => $args['start_date'] ,
            "end_date" => $args['end_date'] ,
            "volume_extra_id" => $args['volume_extra_id'] ,
            "user_id" => $args['user_id'],
        ];
        $is_exist= UserVolumeExtra::where($UserVolumeExtraModel)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("UserVolumeExtra-CREATE-RECORD_IS_EXIST");
         }
        $UserVolumeExtraResult_result=UserVolumeExtra::create($UserVolumeExtraModel);
        return $UserVolumeExtraResult_result;
    }
}