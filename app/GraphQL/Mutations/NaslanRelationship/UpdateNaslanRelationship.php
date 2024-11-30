<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;


final class UpdateNaslanRelationship
{
    use AuthUserTrait;
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
        $this->userId = $this->getUserId();

        //args["user_id_creator"]=$user_id;
        $NaslanRelationshipResult=NaslanRelationship::find($args['id']);
        
        if(!$NaslanRelationshipResult)
        {
            return Error::createLocatedError("NaslanRelationship-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $NaslanRelationshipResult_filled= $NaslanRelationshipResult->fill($args);
        $NaslanRelationshipResult->save();       
       
        return $NaslanRelationshipResult;

        
    }
}