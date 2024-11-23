<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdatePersonChild
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
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        //args["user_id_creator"]=$this->userId;
        $PersonChildResult=PersonChild::find($args['id']);
        $personChildmodel=$args;
        $personChildmodel['editor_id']=$this->userId;
        
        if(!$PersonChildResult)
        {
            return Error::createLocatedError("PersonChild-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $PersonChildResult_filled= $PersonChildResult->fill($personChildmodel);
        $PersonChildResult->save();       
       
        return $PersonChildResult;

        
    }
}