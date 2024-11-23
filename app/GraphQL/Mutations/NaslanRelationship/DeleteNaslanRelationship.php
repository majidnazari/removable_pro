<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteNaslanRelationship
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
    public function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $NaslanRelationshipResult=NaslanRelationship::find($args['id']);
        
        if(!$NaslanRelationshipResult)
        {
            return Error::createLocatedError("NaslanRelationship-DELETE-RECORD_NOT_FOUND");
        }

        $NaslanRelationshipResult->editor_id= $this->userId;
        $NaslanRelationshipResult->save();

        $NaslanRelationshipResult_filled= $NaslanRelationshipResult->delete();  
        return $NaslanRelationshipResult;

        
    }
}