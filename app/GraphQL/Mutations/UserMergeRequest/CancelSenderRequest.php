<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class CancelSenderRequest
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
    public function resolveCancelSenderRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }
        //args["user_id_creator"]=$this->userId;

        $UserMergeRequestResult=UserMergeRequest::where( 'creator_id',$this->userId)->first();
        
        if(!$UserMergeRequestResult)
        {
            return Error::createLocatedError("UserSendeRequest-UPDATE-RECORD_NOT_FOUND");
        }
        $UserMergeRequestResult->editor_id=$this->userId;
        
        $UserMergeRequestResult_filled= $UserMergeRequestResult->fill($args);
        $UserMergeRequestResult->save();       
       
        return $UserMergeRequestResult;

        
    }
}