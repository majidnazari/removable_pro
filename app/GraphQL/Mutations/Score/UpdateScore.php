<?php

namespace App\GraphQL\Mutations\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdateScore
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
    public function resolveScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }
        //args["user_id_creator"]=$this->userId;
        $ScoreResult=Score::find($args['id']);
        
        if(!$ScoreResult)
        {
            return Error::createLocatedError("Score-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        
        $ScoreResult_filled= $ScoreResult->fill($args);
        $ScoreResult->save();       
       
        return $ScoreResult;

        
    }
}