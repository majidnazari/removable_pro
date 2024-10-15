<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateAddress
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveAddress($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $AddressResult=Address::find($args['id']);
        
        if(!$AddressResult)
        {
            return Error::createLocatedError("Address-UPDATE-RECORD_NOT_FOUND");
        }
        $AddressResult_filled= $AddressResult->fill($args);
        $AddressResult->save();       
       
        return $AddressResult;

        
    }
}