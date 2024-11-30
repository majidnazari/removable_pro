<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;
use Log;

final class CreateUserMergeRequest
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

        $UserMergeRequestResult=[
            "status" => $args[' '] ?? Status::Active,
            "title" => $args['title'],
            "description" => $args['description']   ?? ""          
        ];
        $is_exist= UserMergeRequest::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("UserMergeRequest-CREATE-RECORD_IS_EXIST");
         }
        $UserMergeRequestResult_result=UserMergeRequest::create($UserMergeRequestResult);
        return $UserMergeRequestResult_result;
    }
}