<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;
final class DeletePersonMarriage
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
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-DELETE-RECORD_NOT_FOUND");
        }
        if ($PersonMarriageResult->PersonChild()->exists()) 
        {
            return Error::createLocatedError("PersonMarriage-HAS_CHILDREN!");

        }

        $PersonMarriageResult->editor_id= $this->userId;
        $PersonMarriageResult->save(); 

        $PersonMarriageResult_filled= $PersonMarriageResult->delete();  
        return $PersonMarriageResult;

        
    }
}