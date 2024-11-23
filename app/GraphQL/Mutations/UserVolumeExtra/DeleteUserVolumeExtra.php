<?php

namespace App\GraphQL\Mutations\UserVolumeExtra;

use App\Models\UserVolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;


final class DeleteUserVolumeExtra
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
    public function resolveUserVolumeExtra($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $UserVolumeExtraResult=UserVolumeExtra::find($args['id']);
        
        if(!$UserVolumeExtraResult)
        {
            return Error::createLocatedError("UserVolumeExtra-DELETE-RECORD_NOT_FOUND");
        }

        $UserVolumeExtraResult->editor_id= $this->userId;
        $UserVolumeExtraResult->save(); 

        $UserVolumeExtraResult_filled= $UserVolumeExtraResult->delete();  
        return $UserVolumeExtraResult;

        
    }
}