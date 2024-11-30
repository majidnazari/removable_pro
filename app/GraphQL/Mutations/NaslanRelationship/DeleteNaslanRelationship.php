<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;


final class DeleteNaslanRelationship
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