<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\MarriageStatus;
use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\SmallClanTrait;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\AuthorizesMutation;
use App\Traits\GetAllBloodUsersRelationInClanFromHeads;
use App\GraphQL\Enums\AuthAction;

use GraphQL\Error\Error;
use Exception;
use App\Events\PersonDeletedEvent;
use Log;

final class DeleteJustOwnPerson
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;
    use PersonAncestryWithCompleteMerge;
    use AuthorizesMutation;
    use GetAllBloodUsersRelationInClanFromHeads;


    public function resolveDeleteJustOwnPerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $personId = $args['id'];


        try {
            $result = $this->getAllBloodUsersInClanFromHeads($this->userId);
            Log::info("the result are:" . json_encode($result));


            if (!empty($result) && is_array($result) && count($result) > 0) {
                // Do something when the array is not empty
                Log::info("Processing the result as it's not empty.");
                $PersonResult = $this->userAccessibility(Person::class, AuthAction::Delete, $args);
            } else {
                Log::info("The result is empty or null.");
                throw new Exception("You must use delete relation person method instead.");
            }



            // DB::transaction(function () use ($PersonResult) {
            //     $person = Person::find( $personId);

            //     if (!$person) {
            //         throw new Exception("Person not found");
            //     }

            //     $person->delete();

            //     // Fire the event to handle other related deletions
            //     event(new PersonDeletedEvent($PersonResult->id));
            // });

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

    }


}
