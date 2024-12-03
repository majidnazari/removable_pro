<?php

namespace App\GraphQL\Mutations\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class DeleteArea
{
    use AuthUserTrait;
    use checkMutationAuthorization;
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
       
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(Area::class, AuthAction::Delete, $args);


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