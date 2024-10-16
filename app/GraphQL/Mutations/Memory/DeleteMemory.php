<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteMemory
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
       // $user_id=auth()->guard('api')->user()->id;        
        $MemoryResult=Memory::find($args['id']);
        
        if(!$MemoryResult)
        {
            return Error::createLocatedError("Memory-DELETE-RECORD_NOT_FOUND");
        }
        $MemoryResult_filled= $MemoryResult->delete();  
        return $MemoryResult;

        
    }
}