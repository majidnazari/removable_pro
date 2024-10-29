<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeletePersonChild
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
       // $user_id=auth()->guard('api')->user()->id;        
        $PersonChildResult=PersonChild::find($args['id']);
        
        if(!$PersonChildResult)
        {
            return Error::createLocatedError("PersonChild-DELETE-RECORD_NOT_FOUND");
        }
        $PersonChildResult_filled= $PersonChildResult->delete();  
        return $PersonChildResult;

        
    }


    public function resolvePersonChildByChildId($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       // $user_id=auth()->guard('api')->user()->id;        
        $PersonChildResult=PersonChild::where('child_id',$args['child_id'])->first();
        
        if(!$PersonChildResult)
        {
            return Error::createLocatedError("PersonChild-DELETE-RECORD_NOT_FOUND");
        }
        $PersonChildResult_filled= $PersonChildResult->delete();  
        return $PersonChildResult;

        
    }}