<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdatePersonDetails
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $PersonDetailsResult=PersonDetail::find($args['id']);
        $PersonDetailsmodel=$args;
        $PersonDetailsmodel['editor_id']=1;
        
        if(!$PersonDetailsResult)
        {
            return Error::createLocatedError("PersonDetails-UPDATE-RECORD_NOT_FOUND");
        }
        $PersonDetailsResult_filled= $PersonDetailsResult->fill($PersonDetailsmodel);
        $PersonDetailsResult->save();       
       
        return $PersonDetailsResult;

        
    }
}