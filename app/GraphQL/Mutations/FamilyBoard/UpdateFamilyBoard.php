<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;
use App\GraphQL\Enums\Status;

final class UpdateFamilyBoard
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;

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
        $this->userAccessibility(FamilyBoard::class, AuthAction::Delete, $args);


        //args["user_id_creator"]=$user_id;
        $FamilyBoardResult=FamilyBoard::find($args['id']);
        
        if(!$FamilyBoardResult)
        {
            return Error::createLocatedError("FamilyBoard-UPDATE-RECORD_NOT_FOUND");
        }
         // Dynamic duplicate check: Pass column(s) and values, exclude current ID
         //$this->checkDuplicate(new FamilyBoard(), ['title' => $args['title'], 'status' => Status::Active], $args['id']);

         $this->checkDuplicate(
            new FamilyBoard(),
            $args,
            ['id','editor_id','created_at', 'updated_at'],
            $args['id']
        );


        $args['editor_id']= $this->userId;
        $FamilyBoardResult_filled= $FamilyBoardResult->fill($args);
        $FamilyBoardResult->save();       
       
        return $FamilyBoardResult;

        
    }
}