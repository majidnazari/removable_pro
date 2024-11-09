<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteFamilyBoard
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
        $FamilyBoardResult=FamilyBoard::find($args['id']);
        
        if(!$FamilyBoardResult)
        {
            return Error::createLocatedError("FamilyBoard-DELETE-RECORD_NOT_FOUND");
        }
        $FamilyBoardResult->editor_id= $user_id;
        $FamilyBoardResult->save();

        $FamilyBoardResult_filled= $FamilyBoardResult->delete();  
        return $FamilyBoardResult;

        
    }
}