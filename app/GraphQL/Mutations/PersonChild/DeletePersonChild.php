<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeletePersonChild
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
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        try {

            $PersonChildResult = $this->userAccessibility(PersonChild::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($PersonChildResult, $args, $this->userId);
        //    $this->userAccessibility(PersonChild::class, AuthAction::Delete, $args);


        //     $PersonChildResult=PersonChild::find($args['id']);

        //     if(!$PersonChildResult)
        //     {
        //         return Error::createLocatedError("PersonChild-DELETE-RECORD_NOT_FOUND");
        //     }

        //     $PersonChildResult->editor_id= $this->userId;
        //     $PersonChildResult->save(); 

        //     $PersonChildResult_filled= $PersonChildResult->delete();  
        //     return $PersonChildResult;

    }


    public function resolvePersonChildByChildId($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $user = Auth::guard('api')->user();

        if (!$this->userId) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        // $this->userId = $this->userId ;       
        $PersonChildResult = PersonChild::where('child_id', $args['child_id'])->first();

        if (!$PersonChildResult) {
            return Error::createLocatedError("PersonChild-DELETE-RECORD_NOT_FOUND");
        }
        $PersonChildResult_filled = $PersonChildResult->delete();
        return $PersonChildResult;


    }
}