<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


final class UpdateFamilyBoard
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;
        $FamilyBoardResult=FamilyBoard::find($args['id']);
        
        if(!$FamilyBoardResult)
        {
            return Error::createLocatedError("FamilyBoard-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $FamilyBoardResult_filled= $FamilyBoardResult->fill($args);
        $FamilyBoardResult->save();       
       
        return $FamilyBoardResult;

        
    }
}