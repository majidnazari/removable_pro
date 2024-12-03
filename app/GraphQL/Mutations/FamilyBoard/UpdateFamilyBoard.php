<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class UpdateFamilyBoard
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
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(FamilyBoard::class, AuthAction::Delete, $args);


        //args["user_id_creator"]=$user_id;
        $FamilyBoardResult=FamilyBoard::find($args['id']);
        
        if(!$FamilyBoardResult)
        {
            return Error::createLocatedError("FamilyBoard-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']= $this->userId;
        $FamilyBoardResult_filled= $FamilyBoardResult->fill($args);
        $FamilyBoardResult->save();       
       
        return $FamilyBoardResult;

        
    }
}