<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;
final class DeletePersonMarriage
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
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        //    $this->userAccessibility(PersonMarriage::class, AuthAction::Delete, $args);


        //     $PersonMarriageResult=PersonMarriage::find($args['id']);

        //     if(!$PersonMarriageResult)
        //     {
        //         return Error::createLocatedError("PersonMarriage-DELETE-RECORD_NOT_FOUND");
        //     }
        //     if ($PersonMarriageResult->PersonChild()->exists()) 
        //     {
        //         return Error::createLocatedError("PersonMarriage-HAS_CHILDREN!");

        //     }

        //     $PersonMarriageResult->editor_id= $this->userId;
        //     $PersonMarriageResult->save(); 

        //     $PersonMarriageResult_filled= $PersonMarriageResult->delete();  
        //     return $PersonMarriageResult;

        try {

            $PersonMarriageResult = $this->userAccessibility(PersonMarriage::class, AuthAction::Delete, $args);

        
        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($PersonMarriageResult, $args, $this->userId);


    }
}