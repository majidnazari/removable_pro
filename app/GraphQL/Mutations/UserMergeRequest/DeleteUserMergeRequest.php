<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;

final class DeleteUserMergeRequest
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
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        
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