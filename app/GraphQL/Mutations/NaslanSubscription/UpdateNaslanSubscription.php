<?php

namespace App\GraphQL\Mutations\NaslanSubscription;

use App\Models\Subscription;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateNaslanSubscription
{
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
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $NaslanSubscriptionResult=Subscription::find($args['id']);
        
        if(!$NaslanSubscriptionResult)
        {
            return Error::createLocatedError("NaslanSubscription-UPDATE-RECORD_NOT_FOUND");
        }
        $NaslanSubscriptionResult_filled= $NaslanSubscriptionResult->fill($args);
        $NaslanSubscriptionResult->save();       
       
        return $NaslanSubscriptionResult;

        
    }
}