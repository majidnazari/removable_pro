<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteFamilyBoard
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        try {

            $FamilyBoardResult = $this->userAccessibility(FamilyBoard::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($FamilyBoardResult, $args, $this->userId);
        // try {

        //     $FamilyBoardResult = $this->userAccessibility(FamilyBoard::class, AuthAction::Delete, $args);

        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
           
        // }

        // // $FamilyBoardResult = FamilyBoard::find($args['id']);

        // // if (!$FamilyBoardResult) {
        // //     return Error::createLocatedError("FamilyBoard-DELETE-RECORD_NOT_FOUND");
        // // }
       
        // $FamilyBoardResult->update([
        //     'editor_id' => $this->userId,
        // ]);
        
        // $FamilyBoardResult->delete();
        
        // return $FamilyBoardResult;


    }
}