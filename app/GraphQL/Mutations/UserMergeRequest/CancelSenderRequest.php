<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class CancelSenderRequest
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCancelSenderRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;

        $UserMergeRequestResult=UserMergeRequest::where( 'creator_id',$user_id)->first();
        
        if(!$UserMergeRequestResult)
        {
            return Error::createLocatedError("UserMergeRequest-UPDATE-RECORD_NOT_FOUND");
        }
        $UserMergeRequestResult->editor_id=$user_id;
        
        $UserMergeRequestResult_filled= $UserMergeRequestResult->fill($args);
        $UserMergeRequestResult->save();       
       
        return $UserMergeRequestResult;

        
    }
}