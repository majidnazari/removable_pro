<?php

namespace App\GraphQL\Mutations\VolumeExtra;

use App\Models\VolumeExtra;
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

final class CreateVolumeExtra
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveVolumeExtra($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $VolumeExtraResult=[
            "status" => $args['status'] ?? Status::None,
            "day_number" => $args['day_number'] ?? 0,
            "description" => $args['description'] ?? "",
            "title" => $args['title'],
        ];
        $is_exist= VolumeExtra::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("VolumeExtra-CREATE-RECORD_IS_EXIST");
         }
        $VolumeExtraResult_result=VolumeExtra::create($VolumeExtraResult);
        return $VolumeExtraResult_result;
    }
}