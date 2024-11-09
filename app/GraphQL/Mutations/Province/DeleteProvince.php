<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteProvince
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;        
        $ProvinceResult=Province::find($args['id']);
        
        if(!$ProvinceResult)
        {
            return Error::createLocatedError("Province-DELETE-RECORD_NOT_FOUND");
        }

        $ProvinceResult->editor_id= $user_id;
        $ProvinceResult->save(); 


        $ProvinceResult_filled= $ProvinceResult->delete();  
        return $ProvinceResult;

        
    }
}