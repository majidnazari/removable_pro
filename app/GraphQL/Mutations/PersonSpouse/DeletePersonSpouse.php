<?php

namespace App\GraphQL\Mutations\PersonSpouse;

use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeletePersonSpouse
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
       // $user_id=auth()->guard('api')->user()->id;        
        $PersonSpouseResult=PersonSpouse::find($args['id']);
        
        if(!$PersonSpouseResult)
        {
            return Error::createLocatedError("PersonSpouse-DELETE-RECORD_NOT_FOUND");
        }
        if ($PersonSpouseResult->PersonChild()->exists()) 
        {
            return Error::createLocatedError("PersonSpouse-HAS_CHILDREN!");

        }

        $PersonSpouseResult_filled= $PersonSpouseResult->delete();  
        return $PersonSpouseResult;

        
    }
}