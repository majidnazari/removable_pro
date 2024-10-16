<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeletePersonDetails
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
       // $user_id=auth()->guard('api')->user()->id;        
        $PersonDetailResult=PersonDetail::find($args['id']);
        
        if(!$PersonDetailResult)
        {
            return Error::createLocatedError("PersonDetail-DELETE-RECORD_NOT_FOUND");
        }
        $PersonDetailResult_filled= $PersonDetailResult->delete();  
        return $PersonDetailResult;

        
    }
}