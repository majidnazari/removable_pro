<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class DeletePerson
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
    public function resolvePerson($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(Person::class, AuthAction::Update, $args);

      
        $PersonResult=Person::find($args['id']);
        
        if(!$PersonResult)
        {
            return Error::createLocatedError("Person-DELETE-RECORD_NOT_FOUND");
        }

        $PersonResult->editor_id=$this->userId;
        $PersonResult->save(); 

        $PersonResult_filled= $PersonResult->delete();  
        return $PersonResult;

        
    }
}