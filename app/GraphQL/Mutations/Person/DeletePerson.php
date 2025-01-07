<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;

final class DeletePerson
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
    public function resolvePerson($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        //    $this->userAccessibility(Person::class, AuthAction::Update, $args);


        //     $PersonResult=Person::find($args['id']);

        //     if(!$PersonResult)
        //     {
        //         return Error::createLocatedError("Person-DELETE-RECORD_NOT_FOUND");
        //     }

        //     $PersonResult->editor_id=$this->userId;
        //     $PersonResult->save(); 

        //     $PersonResult_filled= $PersonResult->delete();  
        //     return $PersonResult;

        try {

            $PersonResult = $this->userAccessibility(Person::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($PersonResult, $args, $this->userId);

    }
}