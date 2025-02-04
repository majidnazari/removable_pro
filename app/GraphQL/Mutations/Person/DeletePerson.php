<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\Traits\SmallClanTrait;
use App\GraphQL\Enums\AuthAction;
use Exception;
use Log;

final class DeletePerson
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    use SmallClanTrait;

    protected $userId;
    protected $personId;

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
        $this->personId = $args['personId'];
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

            Log::info("the user id is:" . $this->userId . "  wants to person id is:" . $this->personId);

            // Log::info("the result of new small clan is :" . json_encode($this->getAllPeopleIdsSmallClan(5)));
            //$this->getAllOwnerIdsSmallClan();
            $owner = $this->getOwnerIdSmallClan($this->personId);

            if ($owner) {
                return Error::createLocatedError("Person-DELETE-THIS_IS_OWNER!");
            } 
            // else if(! $owner){
            //     return Error::createLocatedError("Person-DELETE-PERSON_NOT_FOUND!");

            // }

            $allowedUserIds = $this->getAllUserIdsSmallClan($this->personId);

            Log::info("the allowedUserIds are " . json_encode($allowedUserIds) . " and user id is  ".  $this->userId);
            Log::info("the user searched in :" . in_array($this->userId,$allowedUserIds->toArray()));

            if(in_array($this->userId,$allowedUserIds->toArray())==false)
            {
                return Error::createLocatedError("Person-DELETE-YOU-DON'T-HAVE-PERMISSION!");

            }

            $chldrenIds = $this->getAllChildren($this->personId);
            Log::info("the user searched in :" . json_encode( $chldrenIds));

            if($chldrenIds)
            {
                return Error::createLocatedError("Person-THIS-PERSON-HAS-CHLDREN!");

            }

            $result=Person::find($this->personId);
            $result->delete();

            return $result;

            //$PersonResult = $this->userAccessibility(Person::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        //return $this->updateAndDeleteModel($PersonResult, $args, $this->userId);

    }
}