<?php

namespace App\GraphQL\Mutations\PersonSpouse;

use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdatePersonSpouse
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonSpouse($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        //$user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $PersonSpouseResult=PersonSpouse::find($args['id']);
        $PersonSpousemodel=$args;
        $PersonSpousemodel['editor_id']=1;
        
        if(!$PersonSpouseResult)
        {
            return Error::createLocatedError("PersonSpouse-UPDATE-RECORD_NOT_FOUND");
        }
        $PersonSpouseResult_filled= $PersonSpouseResult->fill($PersonSpousemodel);
        $PersonSpouseResult->save();       
       
        return $PersonSpouseResult;

        
    }
}