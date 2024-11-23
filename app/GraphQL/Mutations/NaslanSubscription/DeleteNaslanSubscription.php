<?php

namespace App\GraphQL\Mutations\NaslanSubscription;

use App\Models\Subscription;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteNaslanSubscription
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
    public function resolveNaslanSubscription($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $NaslanSubscriptionResult=Subscription::find($args['id']);
        
        if(!$NaslanSubscriptionResult)
        {
            return Error::createLocatedError("NaslanSubscription-DELETE-RECORD_NOT_FOUND");
        }

        $NaslanSubscriptionResult->editor_id= $this->userId;
        $NaslanSubscriptionResult->save();

        $NaslanSubscriptionResult_filled= $NaslanSubscriptionResult->delete();  
        return $NaslanSubscriptionResult;

        
    }
}