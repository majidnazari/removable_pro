<?php

namespace App\GraphQL\Mutations\NaslanSubscription;

use App\Models\Subscription;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateNaslanSubscription
{
    public const NONE=0;
    public const ACTIVE=1;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveNaslanSubscription($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $NaslanSubscriptionModel=[
            "status" => $args['status'] ?? self::NONE,
            "title" => $args['title'],
            "day_number" => $args['day_number'],
            "volume_amount" => $args['volume_amount'],
            "description" => $args['description'],
        ];
        $is_exist= Subscription::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("NaslanSubscription-CREATE-RECORD_IS_EXIST");
         }
        $NaslanSubscriptionResult=Subscription::create($NaslanSubscriptionModel);
        return $NaslanSubscriptionResult;
    }
}