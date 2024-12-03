<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

use Exception;
final class DeletePersonMarriage
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
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(PersonMarriage::class, AuthAction::Delete, $args);

       
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-DELETE-RECORD_NOT_FOUND");
        }
        if ($PersonMarriageResult->PersonChild()->exists()) 
        {
            return Error::createLocatedError("PersonMarriage-HAS_CHILDREN!");

        }

        $PersonMarriageResult->editor_id= $this->userId;
        $PersonMarriageResult->save(); 

        $PersonMarriageResult_filled= $PersonMarriageResult->delete();  
        return $PersonMarriageResult;

        
    }
}