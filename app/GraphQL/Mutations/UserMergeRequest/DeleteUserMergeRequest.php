<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteUserMergeRequest
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
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }    
        $this->userId = $user->id;    
        $UserMergeRequestResult=UserMergeRequest::find($args['id']);
        
        if(!$UserMergeRequestResult)
        {
            return Error::createLocatedError("UserMergeRequest-DELETE-RECORD_NOT_FOUND");
        }

        $UserMergeRequestResult->editor_id= $this->userId;
        $UserMergeRequestResult->save(); 

        $UserMergeRequestResult_filled= $UserMergeRequestResult->delete();  
        return $UserMergeRequestResult;

        
    }
}