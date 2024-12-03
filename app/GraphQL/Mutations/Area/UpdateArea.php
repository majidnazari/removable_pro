<?php

namespace App\GraphQL\Mutations\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class UpdateArea
{
    use AuthUserTrait;
    use checkMutationAuthorization;
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
        
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(Area::class, AuthAction::Delete, $args);


        //args["user_id_creator"]=$user_id;
        $AreaResult=Area::find($args['id']);
        
        if(!$AreaResult)
        {
            return Error::createLocatedError("Area-UPDATE-RECORD_NOT_FOUND");
        }
        $AreaResult_filled= $AreaResult->fill($args);
        $AreaResult->save();       
       
        return $AreaResult;

        
    }
}