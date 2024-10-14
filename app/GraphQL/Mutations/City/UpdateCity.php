<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateCity
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCity($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $CityResult=City::find($args['id']);
        
        if(!$CityResult)
        {
            return Error::createLocatedError("City-UPDATE-RECORD_NOT_FOUND");
        }
        $CityResult_filled= $CityResult->fill($args);
        $CityResult->save();       
       
        return $CityResult;

        
    }
}