<?php

namespace App\GraphQL\Mutations\VolumeExtra;

use App\Models\VolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateVolumeExtra
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveVolumeExtra($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $VolumeExtraResult=VolumeExtra::find($args['id']);
        
        if(!$VolumeExtraResult)
        {
            return Error::createLocatedError("VolumeExtra-UPDATE-RECORD_NOT_FOUND");
        }
        $VolumeExtraResult_filled= $VolumeExtraResult->fill($args);
        $VolumeExtraResult->save();       
       
        return $VolumeExtraResult;

        
    }
}