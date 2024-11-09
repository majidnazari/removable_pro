<?php

namespace App\GraphQL\Mutations\UserVolumeExtra;

use App\Models\UserVolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteUserVolumeExtra
{
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
        $user_id=auth()->guard('api')->user()->id;        
        $UserVolumeExtraResult=UserVolumeExtra::find($args['id']);
        
        if(!$UserVolumeExtraResult)
        {
            return Error::createLocatedError("UserVolumeExtra-DELETE-RECORD_NOT_FOUND");
        }

        $UserVolumeExtraResult->editor_id= $user_id;
        $UserVolumeExtraResult->save(); 

        $UserVolumeExtraResult_filled= $UserVolumeExtraResult->delete();  
        return $UserVolumeExtraResult;

        
    }
}