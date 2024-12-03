<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;


use Exception;

final class CancelSenderRequest
{
    use AuthUserTrait;
    use checkMutationAuthorization;
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
        
        $this->user = $this->getUser();

        $this->checkMutationAuthorization(UserMergeRequest::class, AuthAction::Update, $args);


        $UserMergeRequestResult=UserMergeRequest::where( $this->user->creator_id,$this->user->id)->first();
        
        if(!$UserMergeRequestResult)
        {
            return Error::createLocatedError("UserSendeRequest-UPDATE-RECORD_NOT_FOUND");
        }
        $UserMergeRequestResult->editor_id=$this->user->id;
        
        $UserMergeRequestResult_filled= $UserMergeRequestResult->fill($args);
        $UserMergeRequestResult->save();       
       
        return $UserMergeRequestResult;

       

        
    }
}