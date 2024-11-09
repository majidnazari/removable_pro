<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateMemory
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $MemoryResult=Memory::find($args['id']);
        
        if(!$MemoryResult)
        {
            return Error::createLocatedError("Memory-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $MemoryResult_filled= $MemoryResult->fill($args);
        $MemoryResult->save();       
       
        return $MemoryResult;

        
    }
}