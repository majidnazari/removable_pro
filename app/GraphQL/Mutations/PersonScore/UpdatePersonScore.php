<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdatePersonScore
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
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        //args["user_id_creator"]=$this->userId;
        $PersonScoreResult=PersonScore::find($args['id']);
        $PersonScoremodel=$args;
        $PersonScoremodel['editor_id']=$this->userId;
        
        if(!$PersonScoreResult)
        {
            return Error::createLocatedError("PersonScore-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $PersonScoreResult_filled= $PersonScoreResult->fill($PersonScoremodel);
        $PersonScoreResult->save();       
       
        return $PersonScoreResult;

        
    }
}