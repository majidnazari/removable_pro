<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteFamilyBoard
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
        try {

            $FamilyBoardResult = $this->userAccessibility(FamilyBoard::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
           
        }

        // $FamilyBoardResult = FamilyBoard::find($args['id']);

        // if (!$FamilyBoardResult) {
        //     return Error::createLocatedError("FamilyBoard-DELETE-RECORD_NOT_FOUND");
        // }
       
        $FamilyBoardResult->update([
            'editor_id' => $this->userId,
        ]);
        
        $FamilyBoardResult->delete();
        
        return $FamilyBoardResult;


    }
}