<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdatePersonMarriage
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
        //args["user_id_creator"]=$this->userId;
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        $PersonMarriagemodel=$args;
        $PersonMarriagemodel['editor_id']=$this->userId;
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $PersonMarriageResult_filled= $PersonMarriageResult->fill($PersonMarriagemodel);
        $PersonMarriageResult->save();       
       
        return $PersonMarriageResult;

        
    }
}