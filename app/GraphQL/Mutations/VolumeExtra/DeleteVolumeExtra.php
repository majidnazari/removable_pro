<?php

namespace App\GraphQL\Mutations\VolumeExtra;

use App\Models\VolumeExtra;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteVolumeExtra
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
        $VolumeExtraResult=VolumeExtra::find($args['id']);
        
        if(!$VolumeExtraResult)
        {
            return Error::createLocatedError("VolumeExtra-DELETE-RECORD_NOT_FOUND");
        }
        $VolumeExtraResult_filled= $VolumeExtraResult->delete();  
        return $VolumeExtraResult;

        
    }
}