<?php

namespace App\GraphQL\Mutations\UserVolumeExtra;

use App\Models\UserVolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateUserVolumeExtra
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
        //args["user_id_creator"]=$user_id;
        $UserVolumeExtraResult=UserVolumeExtra::find($args['id']);
        
        if(!$UserVolumeExtraResult)
        {
            return Error::createLocatedError("UserVolumeExtra-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $UserVolumeExtraResult_filled= $UserVolumeExtraResult->fill($args);
        $UserVolumeExtraResult->save();       
       
        return $UserVolumeExtraResult;

        
    }
}