<?php

namespace App\GraphQL\Mutations\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteArea
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveArea($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $AreaResult=Area::find($args['id']);
        
        if(!$AreaResult)
        {
            return Error::createLocatedError("Area-DELETE-RECORD_NOT_FOUND");
        }
        $AreaResult_filled= $AreaResult->delete();  
        return $AreaResult;

        
    }
}