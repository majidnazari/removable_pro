<?php

namespace App\GraphQL\Mutations\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteArea
{
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveArea($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;      
        $AreaResult=Area::find($args['id']);
        
        if(!$AreaResult)
        {
            return Error::createLocatedError("Area-DELETE-RECORD_NOT_FOUND");
        }
        $AreaResult->editor_id= $this->userId;
        $AreaResult->save();
        $AreaResult_filled= $AreaResult->delete();  
        return $AreaResult;

        
    }
}