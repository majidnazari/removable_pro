<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteFamilyBoard
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
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;        
        $FamilyBoardResult=FamilyBoard::find($args['id']);
        
        if(!$FamilyBoardResult)
        {
            return Error::createLocatedError("FamilyBoard-DELETE-RECORD_NOT_FOUND");
        }
        $FamilyBoardResult->editor_id=  $this->userId;
        $FamilyBoardResult->save();

        $FamilyBoardResult_filled= $FamilyBoardResult->delete();  
        return $FamilyBoardResult;

        
    }
}