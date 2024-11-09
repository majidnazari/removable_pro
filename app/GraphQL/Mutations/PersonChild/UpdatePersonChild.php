<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdatePersonChild
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $PersonChildResult=PersonChild::find($args['id']);
        $personChildmodel=$args;
        $personChildmodel['editor_id']=1;
        
        if(!$PersonChildResult)
        {
            return Error::createLocatedError("PersonChild-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $PersonChildResult_filled= $PersonChildResult->fill($personChildmodel);
        $PersonChildResult->save();       
       
        return $PersonChildResult;

        
    }
}