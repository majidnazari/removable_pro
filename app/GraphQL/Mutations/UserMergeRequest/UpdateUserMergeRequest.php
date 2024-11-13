<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateUserMergeRequest
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $UserMergeRequestResult=UserMergeRequest::find($args['id']);
        
        if(!$UserMergeRequestResult)
        {
            return Error::createLocatedError("UserMergeRequest-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        
        $UserMergeRequestResult_filled= $UserMergeRequestResult->fill($args);
        $UserMergeRequestResult->save();       
       
        return $UserMergeRequestResult;

        
    }
}