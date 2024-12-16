<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Models\User;

use Illuminate\Support\Facades\Gate;
use Log;

final class GetUserMergeRequests
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        // Get UserMergeRequest model based on user authorization
        $query = $this->getModelByAuthorization(UserMergeRequest::class, $args, true);
        // Apply search filters and ordering
        $query = $this->applySearchFilters( $query, $args);
        return  $query;

       // Log::info("the user merge request is:" . json_encode($userMergeRequest));
        // Make sure the model exists and the user has access
        // if (!$userMergeRequest) {
        //     throw new \Exception("UserMergeRequest not found or you don't have permission.");
        // }
    //     $this->user = $this->getUser();

    //    // $user_id = $this->getUserId();

    //    //Log::info("the user role is :" . $this->user->isAdmin() );
    //     $UserMergeRequests = $this->user->isAdmin()  
    //     ? 
    //     UserMergeRequest::where('deleted_at', null)       
    //     :
    //     UserMergeRequest::where('user_sender_id', $this->user->id)
    //     ->orWhere('user_receiver_id',$this->user->id)
    //     ->where('deleted_at', null)
    //     ;

        

    //     //log::info("the Scores is:" . json_encode($UserMergeRequests));
    //     return $UserMergeRequests;
    }
}